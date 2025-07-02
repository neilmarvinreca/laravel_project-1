@extends('layouts.app')

@section('title', 'Create Supply')

@section('content')
<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Create New Supply</h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
        <a href="{{ route('supplies.index') }}" class="btn btn-secondary shadow-md mr-2">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to Supplies
        </a>
    </div>
</div>

<!-- BEGIN: Create Supply Form -->
<div class="intro-y box p-5 mt-5">
    <form method="POST" action="{{ route('supplies.store') }}" class="grid grid-cols-12 gap-6">
        @csrf
        
        <div class="col-span-12 xl:col-span-6">
            <div class="input-form">
                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text" id="name" name="name" class="form-control w-full @error('name') border-danger @enderror" 
                    value="{{ old('name') }}" required>
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
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                    value="{{ old('quantity') }}" required min="0">
                @error('quantity')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-span-12 xl:col-span-6">
            <div class="input-form">
                <label for="unit" class="form-label">Unit <span class="text-danger">*</span></label>
                <input type="text" id="unit" name="unit" class="form-control w-full @error('unit') border-danger @enderror" 
                    value="{{ old('unit') }}" required>
                @error('unit')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-span-12 xl:col-span-6">
            <div class="input-form">
                <label for="minimum_stock" class="form-label">Minimum Stock <span class="text-danger">*</span></label>
                <input type="number" id="minimum_stock" name="minimum_stock" class="form-control w-full @error('minimum_stock') border-danger @enderror" 
                    value="{{ old('minimum_stock') }}" required min="0">
                @error('minimum_stock')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-span-12 xl:col-span-6">
            <div class="input-form">
                <label for="unit_cost" class="form-label">Unit Cost <span class="text-danger">*</span></label>
                <input type="number" id="unit_cost" name="unit_cost" class="form-control w-full @error('unit_cost') border-danger @enderror" 
                    value="{{ old('unit_cost') }}" required min="0" step="0.01">
                @error('unit_cost')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-span-12 xl:col-span-6">
            <div class="input-form">
                <label for="location" class="form-label">Location</label>
                <input type="text" id="location" name="location" class="form-control w-full @error('location') border-danger @enderror" 
                    value="{{ old('location') }}">
                @error('location')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-span-12 xl:col-span-6">
            <div class="input-form">
                <label for="supplier" class="form-label">Supplier</label>
                <input type="text" id="supplier" name="supplier" class="form-control w-full @error('supplier') border-danger @enderror" 
                    value="{{ old('supplier') }}">
                @error('supplier')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-span-12 xl:col-span-12">
            <div class="input-form">
                <label for="supplier_contact" class="form-label">Supplier Contact</label>
                <input type="text" id="supplier_contact" name="supplier_contact" class="form-control w-full @error('supplier_contact') border-danger @enderror" 
                    value="{{ old('supplier_contact') }}">
                @error('supplier_contact')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-span-12 xl:col-span-12">
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
<!-- END: Create Supply Form -->
@endsection
