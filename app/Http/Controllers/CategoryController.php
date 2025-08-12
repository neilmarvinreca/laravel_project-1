<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource with search and pagination.
     */
    public function index(Request $request)
    {
        $query = Category::withCount('supplies')
            ->whereNull('deleted_at')
            ->orderBy('categoryID', 'asc');
        
        // Search functionality
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('categoryName', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        $categories = $query->paginate(10);
        
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $this->validateCategory($request);
            
            // Create the category (slug generation is handled in the model if needed)
            $category = Category::create($validated);
            
            Log::info('Category created successfully', [
                'category_id' => $category->categoryID ?? $category->id,
                'by_user' => auth()->id()
            ]);
            
            return redirect()
                ->route('categories.index')
                ->with('success', 'Category created successfully.');
                
        } catch (\Exception $e) {
            Log::error('Error creating category: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);
            
            return back()
                ->withInput()
                ->with('error', 'Error creating category: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        // Load the category with the count of related supplies
        $category->loadCount('supplies');
        return view('categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        try {
            $validated = $this->validateCategory($request, $category->categoryID ?? $category->id);
            
            // Update the category (slug generation is handled in the model if needed)
            $category->update($validated);
            
            Log::info('Category updated', [
                'category_id' => $category->categoryID ?? $category->id,
                'by_user' => auth()->id()
            ]);
            
            return redirect()
                ->route('categories.edit', $category)
                ->with('success', 'Successfully updated the Category');
                
        } catch (\Exception $e) {
            Log::error('Error updating category: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Error updating category. Please try again.');
        }
    }

    /**
     * Archive the specified category (soft delete).
     */
    public function archive(Category $category)
    {
        try {
            $category->delete();
            
            Log::info('Category archived', [
                'category_id' => $category->categoryID ?? $category->id,
                'by_user' => auth()->id()
            ]);
            
            return redirect()
                ->route('categories.index')
                ->with('success', 'Category archived successfully.')
                ->with('archive_success', $category->categoryName);
                
        } catch (\Exception $e) {
            Log::error('Error archiving category: ' . $e->getMessage(), [
                'category_id' => $category->categoryID ?? $category->id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()
                ->with('error', 'Error archiving category: ' . $e->getMessage());
        }
    }

    /**
     * Display a listing of archived categories.
     */
    public function archived()
    {
        // Debug: Log that the method was called
        \Log::info('Archived categories method called', [
            'user_id' => auth()->id(),
            'url' => request()->fullUrl()
        ]);

        try {
            $categories = Category::onlyTrashed()
                ->withCount('supplies')
                ->orderBy('deleted_at', 'desc')
                ->paginate(10);

            // Debug: Log the query results
            \Log::info('Archived categories query results', [
                'count' => $categories->total(),
                'items' => $categories->pluck('categoryName')
            ]);
            
            return view('categories.archived', compact('categories'));
        } catch (\Exception $e) {
            // Debug: Log any exceptions
            \Log::error('Error in archived method', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Rethrow the exception for debugging
            throw $e;
        }
    }

    /**
     * Restore the specified archived category.
     */
    public function restore($id)
    {
        try {
            $category = Category::withTrashed()->findOrFail($id);
            $category->restore();
            
            Log::info('Category restored', [
                'category_id' => $category->categoryID ?? $category->id,
                'by_user' => auth()->id()
            ]);
            
            return redirect()
                ->route('categories.archived')
                ->with('success', 'Category restored successfully.');
                
        } catch (\Exception $e) {
            Log::error('Error restoring category: ' . $e->getMessage(), [
                'category_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()
                ->with('error', 'Error restoring category: ' . $e->getMessage());
        }
    }

    /**
     * Permanently delete the specified category.
     */
    public function forceDelete($id)
    {
        try {
            $category = Category::withTrashed()->findOrFail($id);
            
            // Check if category has supplies
            if ($category->supplies()->count() > 0) {
                return back()
                    ->with('error', 'Cannot delete category that has associated supplies.');
            }
            
            $category->forceDelete();
            
            Log::info('Category permanently deleted', [
                'category_id' => $category->categoryID ?? $category->id,
                'by_user' => auth()->id()
            ]);
            
            return redirect()
                ->route('categories.archived')
                ->with('success', 'Category permanently deleted.');
                
        } catch (\Exception $e) {
            Log::error('Error permanently deleting category: ' . $e->getMessage(), [
                'category_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()
                ->with('error', 'Error permanently deleting category: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        return $this->archive($category);
    }
    
    /**
     * Validate category data.
     */
    protected function validateCategory(Request $request, $categoryId = null)
    {
        $rules = [
            'categoryName' => [
                'required',
                'string',
                'max:100',
                Rule::unique('categories', 'categoryName')->ignore($categoryId, 'categoryID')
            ],
            'description' => 'nullable|string|max:255',
        ];
        
        return $request->validate($rules);
    }
}