@extends('layouts.app')

@section('title', 'Edit Supply')

@section('content')
<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Edit Supply</h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
        <a href="{{ route('supplies.index') }}" class="btn btn-secondary shadow-md mr-2">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to Supplies
        </a>
    </div>
</div>

<!-- BEGIN: Edit Supply Form -->
<div class="intro-y box p-5 mt-5">
    <form method="POST" action="{{ route('supplies.update', $supply) }}" class="grid grid-cols-12 gap-6">
        @csrf
        @method('PUT')
        
        <div class="col-span-12 xl:col-span-6">
            <div class="input-form">
                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text" id="name" name="name" class="form-control w-full @error('name') border-danger @enderror" 
                    value="{{ old('name', $supply->name) }}" required>
                @error('name')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-span-12 xl:col-span-6">
            <div class="input-form">
                <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                <select id="category_id" name="category_id" class="form-select w-full @error('category_id') border-danger @enderror" required>
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $supply->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-span-12 xl:col-span-6">
            <div class="input-form">
                <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                <input type="number" id="quantity" name="quantity" class="form-control w-full @error('quantity') border-danger @enderror" 
                    value="{{ old('quantity', $supply->quantity) }}" required min="0">
                @error('quantity')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-span-12 xl:col-span-6">
            <div class="input-form">
                <label for="unit" class="form-label">Unit <span class="text-danger">*</span></label>
                <input type="text" id="unit" name="unit" class="form-control w-full @error('unit') border-danger @enderror" 
                    value="{{ old('unit', $supply->unit) }}" required>
                @error('unit')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-span-12 xl:col-span-6">
            <div class="input-form">
                <label for="minimum_quantity" class="form-label">Minimum Quantity <span class="text-danger">*</span></label>
                <input type="number" id="minimum_quantity" name="minimum_quantity" class="form-control w-full @error('minimum_quantity') border-danger @enderror" 
                    value="{{ old('minimum_quantity', $supply->minimum_quantity) }}" required min="0">
                @error('minimum_quantity')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-span-12 xl:col-span-6">
            <div class="input-form">
                <label for="description" class="form-label">Description</label>
                <textarea id="description" name="description" class="form-control w-full @error('description') border-danger @enderror" 
                    rows="4">{{ old('description', $supply->description) }}</textarea>
                @error('description')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-span-12 flex items-center justify-center sm:justify-end mt-5">
            <button type="submit" class="btn btn-primary w-24 mr-2">Update</button>
            <a href="{{ route('supplies.index') }}" class="btn btn-secondary w-24">Cancel</a>
        </div>
    </form>
</div>
<!-- END: Edit Supply Form -->
@endsection
