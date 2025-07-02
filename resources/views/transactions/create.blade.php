@extends('layouts.app')

@section('title', 'Create Transaction')

@section('content')
<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Create New Transaction</h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
        <a href="{{ route('transactions.index') }}" class="btn btn-secondary shadow-md mr-2">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to Transactions
        </a>
    </div>
</div>

<!-- BEGIN: Create Transaction Form -->
<div class="intro-y box p-5 mt-5">
    <form method="POST" action="{{ route('transactions.store') }}" class="grid grid-cols-12 gap-6">
        @csrf
        
        <div class="col-span-12">
            <div class="input-form">
                <label for="supply_id" class="form-label">Supply <span class="text-danger">*</span></label>
                <select id="supply_id" name="supply_id" class="form-select w-full @error('supply_id') border-danger @enderror" required>
                    <option value="">Select Supply</option>
                    @foreach($supplies as $supply)
                        <option value="{{ $supply->id }}" {{ old('supply_id') == $supply->id ? 'selected' : '' }}>
                            {{ $supply->name }} (Current Stock: {{ $supply->quantity }})
                        </option>
                    @endforeach
                </select>
                @error('supply_id')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-span-12">
            <div class="input-form">
                <label for="type" class="form-label">Transaction Type <span class="text-danger">*</span></label>
                <select id="type" name="type" class="form-select w-full @error('type') border-danger @enderror" required>
                    <option value="in" {{ old('type') == 'in' ? 'selected' : '' }}>Stock In</option>
                    <option value="out" {{ old('type') == 'out' ? 'selected' : '' }}>Stock Out</option>
                </select>
                @error('type')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-span-12">
            <div class="input-form">
                <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                <input type="number" id="quantity" name="quantity" class="form-control w-full @error('quantity') border-danger @enderror" 
                    value="{{ old('quantity') }}" required min="1">
                @error('quantity')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-span-12">
            <div class="input-form">
                <label for="remarks" class="form-label">Remarks</label>
                <textarea id="remarks" name="remarks" class="form-control w-full @error('remarks') border-danger @enderror" 
                    rows="4">{{ old('remarks') }}</textarea>
                @error('remarks')
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
<!-- END: Create Transaction Form -->
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.querySelector('#type');
    const supplySelect = document.querySelector('#supply_id');
    const quantityInput = document.querySelector('#quantity');

    // Add validation for stock out transactions
    document.querySelector('form').addEventListener('submit', function(event) {
        if (typeSelect.value === 'out') {
            const selectedOption = supplySelect.options[supplySelect.selectedIndex];
            const currentStock = parseInt(selectedOption.textContent.match(/Current Stock: (\d+)/)[1]);
            const requestedQuantity = parseInt(quantityInput.value);

            if (requestedQuantity > currentStock) {
                event.preventDefault();
                alert('Cannot stock out more than current stock quantity.');
                return false;
            }
        }
    });
});
</script>
@endpush 