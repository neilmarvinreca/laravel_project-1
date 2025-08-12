<!-- Create Category Modal -->
<div id="createCategoryModal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">
                    Add New Category
                </h2>
                <button class="btn btn-outline-secondary hidden sm:flex" data-tw-dismiss="modal">
                    <i data-lucide="x" class="w-4 h-4 mr-2"></i> Close
                </button>
            </div>
            <div class="modal-body p-5">
                <form id="createCategoryForm" action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                        <input 
                            type="text" 
                            id="categoryName" 
                            name="categoryName" 
                            class="form-control w-full @error('categoryName') border-danger @enderror" 
                            value="{{ old('categoryName') }}" 
                            placeholder="e.g. Office Supplies, Electronics"
                            required
                            autofocus
                        >
                        @error('categoryName')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="description" class="form-label">Description</label>
                        <textarea 
                            id="description" 
                            name="description" 
                            class="form-control w-full @error('description') border-danger @enderror" 
                            rows="3"
                            placeholder="Enter a brief description of this category..."
                        >{{ old('description') }}</textarea>
                        @error('description')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="flex justify-end mt-5">
                        <button type="button" class="btn btn-outline-secondary w-24 mr-1" data-tw-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary w-24">
                            <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Reset form when modal is closed
    document.getElementById('createCategoryModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('createCategoryForm').reset();
        // Clear validation errors
        const errorElements = document.querySelectorAll('.text-danger');
        errorElements.forEach(el => el.textContent = '');
        
        const inputElements = document.querySelectorAll('.border-danger');
        inputElements.forEach(el => el.classList.remove('border-danger'));
    });
    
    // Show modal if there are validation errors
    @if($errors->any() && (old('_token') && !session('success')))
        document.addEventListener('DOMContentLoaded', function() {
            const modal = new bootstrap.Modal(document.getElementById('createCategoryModal'));
            modal.show();
        });
    @endif
</script>
@endpush
