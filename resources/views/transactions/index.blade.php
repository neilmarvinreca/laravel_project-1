@extends('layouts.app')

@section('title', 'Transaction History')

@section('content')
<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Transaction History</h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
        <a href="{{ route('transactions.create') }}" class="btn btn-primary shadow-md mr-2">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Add Transaction
        </a>
    </div>
</div>

<!-- BEGIN: Transaction List -->
<div class="intro-y box p-5 mt-5">
    <div class="overflow-x-auto">
        <table class="table table-report -mt-2">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">Date</th>
                    <th class="whitespace-nowrap">Supply</th>
                    <th class="whitespace-nowrap">Type</th>
                    <th class="whitespace-nowrap">Quantity</th>
                    <th class="whitespace-nowrap">Remarks</th>
                    <th class="whitespace-nowrap text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $transaction)
                    <tr class="intro-x">
                        <td>{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                        <td>{{ $transaction->supply?->name ?? 'Deleted Supply' }}</td>
                        <td>
                            @if($transaction->type === 'in')
                                <div class="flex items-center text-success">
                                    <i data-lucide="arrow-down-circle" class="w-4 h-4 mr-1"></i> Stock In
                                </div>
                            @else
                                <div class="flex items-center text-danger">
                                    <i data-lucide="arrow-up-circle" class="w-4 h-4 mr-1"></i> Stock Out
                                </div>
                            @endif
                        </td>
                        <td>{{ $transaction->quantity }}</td>
                        <td>{{ $transaction->remarks }}</td>
                        <td class="table-report__action w-56">
                            <div class="flex justify-center items-center">
                                <a href="{{ route('transactions.edit', $transaction) }}" class="btn btn-sm btn-primary mr-2">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this transaction?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No transactions found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- BEGIN: Pagination -->
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-5">
        {{ $transactions->links() }}
    </div>
    <!-- END: Pagination -->
</div>
<!-- END: Transaction List -->

<!-- BEGIN: Add Transaction Modal -->
<div id="addTransactionModal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('transactions.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h2 class="font-medium text-base mr-auto">Add New Transaction</h2>
                    <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                    <div class="col-span-12">
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

                    <div class="col-span-12">
                        <label for="type" class="form-label">Transaction Type <span class="text-danger">*</span></label>
                        <select id="type" name="type" class="form-select w-full @error('type') border-danger @enderror" required>
                            <option value="in" {{ old('type') == 'in' ? 'selected' : '' }}>Stock In</option>
                            <option value="out" {{ old('type') == 'out' ? 'selected' : '' }}>Stock Out</option>
                        </select>
                        @error('type')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-span-12">
                        <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                        <input type="number" id="quantity" name="quantity" class="form-control @error('quantity') border-danger @enderror" 
                            value="{{ old('quantity') }}" required min="1">
                        @error('quantity')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-span-12">
                        <label for="remarks" class="form-label">Remarks</label>
                        <textarea id="remarks" name="remarks" class="form-control @error('remarks') border-danger @enderror" 
                            rows="3">{{ old('remarks') }}</textarea>
                        @error('remarks')
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
<!-- END: Add Transaction Modal -->

<!-- BEGIN: Edit Transaction Modals -->
@foreach($transactions as $transaction)
<div id="editTransactionModal{{ $transaction->id }}" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('transactions.update', $transaction) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h2 class="font-medium text-base mr-auto">Edit Transaction</h2>
                    <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                    <div class="col-span-12">
                        <label class="form-label">Supply</label>
                        <input type="text" class="form-control bg-slate-100" value="{{ $transaction->supply?->name ?? 'Deleted Supply' }}" disabled>
                        <input type="hidden" name="supply_id" value="{{ $transaction->supply_id }}">
                    </div>

                    <div class="col-span-12">
                        <label class="form-label">Transaction Type</label>
                        <input type="text" class="form-control bg-slate-100" value="{{ $transaction->type === 'in' ? 'Stock In' : 'Stock Out' }}" disabled>
                        <input type="hidden" name="type" value="{{ $transaction->type }}">
                    </div>

                    <div class="col-span-12">
                        <label for="edit_quantity{{ $transaction->id }}" class="form-label">Quantity <span class="text-danger">*</span></label>
                        <input type="number" id="edit_quantity{{ $transaction->id }}" name="quantity" 
                            class="form-control @error('quantity') border-danger @enderror" 
                            value="{{ $transaction->quantity }}" required min="1">
                        @error('quantity')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-span-12">
                        <label for="edit_remarks{{ $transaction->id }}" class="form-label">Remarks</label>
                        <textarea id="edit_remarks{{ $transaction->id }}" name="remarks" 
                            class="form-control @error('remarks') border-danger @enderror" 
                            rows="3">{{ $transaction->remarks }}</textarea>
                        @error('remarks')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    @if($transaction->type === 'out')
                    <div class="col-span-12">
                        <div class="alert alert-info show">
                            <i data-lucide="info" class="w-6 h-6 mr-2"></i>
                            Note: For stock out transactions, you can only decrease the quantity.
                        </div>
                    </div>
                    @endif
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
<!-- END: Edit Transaction Modals -->

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
                const inputs = form.querySelectorAll('input[type="number"], textarea');
                inputs.forEach(input => {
                    input.dataset.originalValue = input.value;
                });
            }
        });

        // Reset to original values if modal is closed without saving
        el.addEventListener('hidden.tw.modal', function(event) {
            const form = el.querySelector('form');
            if (form) {
                const inputs = form.querySelectorAll('input[type="number"], textarea');
                inputs.forEach(input => {
                    if (input.dataset.originalValue) {
                        input.value = input.dataset.originalValue;
                    }
                });
                // Remove any error messages
                const errorMessages = form.querySelectorAll('.text-danger');
                errorMessages.forEach(msg => msg.remove());
                // Remove error borders
                const errorInputs = form.querySelectorAll('.border-danger');
                errorInputs.forEach(input => input.classList.remove('border-danger'));
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

        // Add validation for stock out transactions in edit forms
        if (form.querySelector('input[name="type"][value="out"]')) {
            const quantityInput = form.querySelector('input[name="quantity"]');
            const originalQuantity = parseInt(quantityInput.value);

            form.addEventListener('submit', function(event) {
                const newQuantity = parseInt(quantityInput.value);
                if (newQuantity > originalQuantity) {
                    event.preventDefault();
                    alert('For stock out transactions, you can only decrease the quantity.');
                    quantityInput.value = originalQuantity;
                    submitButton.disabled = false;
                    return false;
                }
            });
        }
    });

    // Add validation for stock out transactions in add form
    const addForm = document.querySelector('#addTransactionModal form');
    if (addForm) {
        const typeSelect = addForm.querySelector('#type');
        const supplySelect = addForm.querySelector('#supply_id');
        const quantityInput = addForm.querySelector('#quantity');

        addForm.addEventListener('submit', function(event) {
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
    }
});
</script>
@endpush
