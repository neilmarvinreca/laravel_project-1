@extends('layouts.app')

@section('title', 'Categories')

@section('content')
<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Category Management</h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
        <a href="{{ route('categories.create') }}" class="btn btn-primary shadow-md mr-2">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Add New Category
        </a>
    </div>
</div>

<!-- Categories Table -->
<div class="intro-y box p-5 mt-5">
    <div class="overflow-x-auto">
        <table class="table table-report -mt-2">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">Name</th>
                    <th class="whitespace-nowrap">Description</th>
                    <th class="text-center whitespace-nowrap">Supplies Count</th>
                    <th class="text-center whitespace-nowrap">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr class="intro-x">
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->description }}</td>
                        <td class="text-center">{{ $category->supplies_count }}</td>
                        <td class="table-report__action w-56">
                            <div class="flex justify-center items-center">
                                <a href="{{ route('categories.edit', $category) }}" class="flex items-center mr-3">
                                    <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Edit
                                </a>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="flex items-center text-danger"
                                        onclick="return confirmDelete({{ $category->supplies_count }})">
                                        <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No categories found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- Pagination -->
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-5">
        {{ $categories->links() }}
    </div>
</div>

<!-- Create Category Modal -->
<div id="createCategoryModal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h2 class="font-medium text-base mr-auto">Add New Category</h2>
                    <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                    <div class="col-span-12">
                        <label for="name" class="form-label">Category Name</label>
                        <input type="text" id="name" name="name" class="form-control @error('name') border-danger @enderror" 
                            value="{{ old('name') }}" required>
                        @error('name')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-span-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" class="form-control @error('description') border-danger @enderror" 
                            rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
                    <button type="submit" class="btn btn-primary w-20">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Category Modals -->
@foreach($categories as $category)
    <div id="editCategoryModal{{ $category->id }}" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('categories.update', $category) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h2 class="font-medium text-base mr-auto">Edit Category</h2>
                        <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-12">
                            <label for="edit_name_{{ $category->id }}" class="form-label">Category Name</label>
                            <input type="text" id="edit_name_{{ $category->id }}" name="name" 
                                class="form-control @error('name') border-danger @enderror"
                                value="{{ $category->name }}" required>
                            @error('name')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-span-12">
                            <label for="edit_description_{{ $category->id }}" class="form-label">Description</label>
                            <textarea id="edit_description_{{ $category->id }}" name="description" 
                                class="form-control @error('description') border-danger @enderror"
                                rows="3">{{ $category->description }}</textarea>
                            @error('description')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
                        <button type="submit" class="btn btn-primary w-20">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Lucide icons
    lucide.createIcons();
    
    // Initialize all modals
    const modals = document.querySelectorAll('.modal');
    modals.forEach(function(el) {
        const modal = tailwind.Modal.getOrCreateInstance(el);
        
        // Store original values when modal opens
        el.addEventListener('show.tw.modal', function(event) {
            const form = el.querySelector('form');
            if (form) {
                const inputs = form.querySelectorAll('input[type="text"], textarea');
                inputs.forEach(input => {
                    input.dataset.originalValue = input.value;
                });
            }
        });

        // Reset to original values if modal is closed without saving
        el.addEventListener('hidden.tw.modal', function(event) {
            const form = el.querySelector('form');
            if (form) {
                const inputs = form.querySelectorAll('input[type="text"], textarea');
                inputs.forEach(input => {
                    if (input.dataset.originalValue) {
                        input.value = input.dataset.originalValue;
                    }
                });
            }
        });
    });

    // Handle form submissions
    const forms = document.querySelectorAll('form');
    forms.forEach(function(form) {
        form.addEventListener('submit', function(event) {
            const submitButton = form.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
            }
        });
    });
});

function confirmDelete(suppliesCount) {
    if (suppliesCount > 0) {
        alert('This category cannot be deleted because it has ' + suppliesCount + ' supplies associated with it. Please move or delete the supplies first.');
        return false;
    }
    return confirm('Are you sure you want to delete this category?');
}
</script>
@endpush
