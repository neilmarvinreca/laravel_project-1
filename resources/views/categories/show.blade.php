@extends('layouts.app')

@section('title', 'View Category')

@section('content')
<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Category Details
    </h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
        <a href="{{ route('categories.index') }}" class="btn btn-secondary shadow-md mr-2">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to Categories
        </a>
    </div>
</div>

<!-- BEGIN: Category Details -->
<div class="intro-y box p-5 mt-5">
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            <div class="border-b border-gray-200 dark:border-dark-5 pb-5">
                <div class="mb-4">
                    <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 mb-1">Category Name:</h3>
                    <h2 class="text-lg font-medium">{{ $category->categoryName }}</h2>
                </div>
                <div class="mt-4">
                    <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 mb-1">Description:</h3>
                    <div class="whitespace-pre text-gray-600 dark:text-gray-400 font-medium bg-gray-50 dark:bg-dark-2 p-3 rounded-md mt-2 font-mono text-sm">
                        {!! nl2br(e($category->description)) !!}
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-span-12">
            <div class="mt-4">
                <h3 class="text-gray-600 dark:text-gray-400 font-medium">Statistics</h3>
                <div class="mt-2">
                    <span class="px-3 py-1 rounded-full text-sm font-medium bg-primary/10 text-primary">
                        {{ $category->supplies_count }} {{ Str::plural('item', $category->supplies_count) }} in this category
                    </span>
                </div>
                <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Created: {{ optional($category->created_at)->format('M d, Y') }} ({{ optional($category->created_at)->diffForHumans() }})
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: Category Details -->
@endsection
