@extends('layouts.app')

@section('title', 'Edit Category')

@section('content')
<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Edit Category</h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
        <a href="{{ route('categories.index') }}" class="btn btn-secondary shadow-md mr-2">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to Categories
        </a>
    </div>
</div>

@if(session('success'))
    <div class="bg-maroon-800 text-white p-4 rounded-md mb-4 flex items-center">
        <i data-lucide="check-circle" class="w-5 h-5 mr-2"></i>
        <span>{{ session('success') }}</span>
    </div>
@elseif(session('error'))
    <div class="alert alert-danger-soft show flex items-center mb-2" role="alert">
        <i data-lucide="alert-octagon" class="w-6 h-6 mr-2"></i> {{ session('error') }}
    </div>
@endif

<!-- BEGIN: Edit Category Form -->
<div class="intro-y box p-5 mt-5">
    <form method="POST" action="{{ route('categories.update', $category) }}" class="grid grid-cols-12 gap-6">
        @csrf
        @method('PUT')
        
        <div class="col-span-12">
            <div class="input-form">
                <label for="categoryName" class="form-label">Category Name <span class="text-danger">*</span></label>
                <input type="text" id="categoryName" name="categoryName" class="form-control w-full @error('categoryName') border-danger @enderror" 
                    value="{{ old('categoryName', $category->categoryName) }}" required>
                @error('categoryName')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-span-12">
            <div class="input-form">
                <label for="description" class="form-label">Description</label>
                <textarea id="description" name="description" class="form-control w-full whitespace-pre-wrap @error('description') border-danger @enderror" 
                    rows="6" placeholder="Enter description here...">{{ old('description', $category->description) }}</textarea>
                @error('description')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-span-12 flex items-center justify-center sm:justify-end mt-5">
            <button type="submit" class="btn btn-primary w-24 mr-2">Update</button>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary w-24">Cancel</a>
        </div>
    </form>
</div>
<!-- END: Edit Category Form -->
@endsection
