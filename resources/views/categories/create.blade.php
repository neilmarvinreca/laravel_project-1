@extends('layouts.app')

@section('title', 'Create Category')

@section('content')
<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Create New Category</h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
        <a href="{{ route('categories.index') }}" class="btn btn-secondary shadow-md mr-2">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to Categories
        </a>
    </div>
</div>

<!-- BEGIN: Create Category Form -->
<div class="intro-y box p-5 mt-5">
    <form method="POST" action="{{ route('categories.store') }}" class="grid grid-cols-12 gap-6">
        @csrf
        
        <div class="col-span-12">
            <div class="input-form">
                <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                <input type="text" id="name" name="name" class="form-control w-full @error('name') border-danger @enderror" 
                    value="{{ old('name') }}" required>
                @error('name')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-span-12">
            <div class="input-form">
                <label for="description" class="form-label">Description</label>
                <textarea id="description" name="description" class="form-control w-full @error('description') border-danger @enderror" 
                    rows="4">{{ old('description') }}</textarea>
                @error('description')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-span-12 flex items-center justify-center sm:justify-end mt-5">
            <button type="submit" class="btn btn-primary w-24 mr-2">Save</button>
            <button type="reset" class="btn btn-secondary w-24">Reset</button>
        </div>
    </form>
</div>
<!-- END: Create Category Form -->
@endsection 