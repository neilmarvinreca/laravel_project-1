@extends('layouts.app')

@section('title', 'Archived Deployed Items')
<link href="{{ asset('dist/images/logodssc.png') }}" rel="shortcut icon">

@section('content')
<div class="max-w-7xl mx-auto py-8">
    <div class="intro-y flex flex-col sm:flex-row items-center mt-8 mb-6">
        <h2 class="text-lg font-medium mr-auto">Archived Deployed Items</h2>
        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
            <a href="{{ route('deployed-items.index') }}" class="btn btn-secondary shadow-md mr-2">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to Active Items
            </a>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        @if($deployedItems->isEmpty())
            <div class="text-center py-8">
                <i data-lucide="archive-x" class="w-16 h-16 mx-auto text-gray-400"></i>
                <h3 class="mt-2 text-lg font-medium">No archived items</h3>
                <p class="text-gray-500">There are no archived deployed items to display.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="table table-report">
                    <thead>
                        <tr>
                            <th class="whitespace-nowrap">Item Details</th>
                            <th>Department</th>
                            <th class="text-center">Deployed Date</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Archived At</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($deployedItems as $item)
                            @php
                                $itemName = $item->itemName ?? ($item->supply->name ?? 'N/A');
                                $itemDescription = $item->itemDescription ?? ($item->supply->description ?? 'No description');
                                $departmentName = $item->department->officename ?? 'N/A';
                                $departmentId = $item->department->departmentID ?? '';
                                $deployedDate = $item->dateDeployed ? \Carbon\Carbon::parse($item->dateDeployed)->format('M d, Y') : 'N/A';
                                $archivedDate = $item->deleted_at ? $item->deleted_at->format('M d, Y') : 'N/A';
                                $archivedTimeAgo = $item->deleted_at ? $item->deleted_at->diffForHumans() : '';
                            @endphp
                            <tr>
                                <td class="whitespace-nowrap">
                                    <div class="font-medium">
                                        {{ $itemName }}
                                    </div>
                                    <div class="text-slate-500 text-xs mt-0.5">
                                        {{ $itemDescription }}
                                    </div>
                                </td>
                                <td>
                                    <div class="font-medium">{{ $departmentName }}</div>
                                    @if($departmentId)
                                        <div class="text-slate-500 text-xs">{{ $departmentId }}</div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{ $deployedDate }}
                                </td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-center">
                                    <span class="status-badge status-{{ strtolower($item->status) }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="text-xs text-gray-500">
                                        {{ $archivedDate }}
                                        <div class="text-gray-400">{{ $archivedTimeAgo }}</div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="flex justify-center space-x-2">
                                        <form id="restore-form-{{ $item->deployedID ?? $item->id }}" action="{{ route('deployed-items.restore', ['id' => $item->deployedID ?? $item->id]) }}" method="POST" class="inline mx-2">
                                            @csrf
                                            @method('POST')
                                            <input type="hidden" name="_method" value="POST">
                                            <button type="button"
                                                    onclick="confirmRestore('{{ addslashes($itemName) }}', this)" 
                                                    class="btn btn-sm w-8 h-8 flex items-center justify-center p-0" 
                                                    style="background-color: #10b981; border-color: #10b981; color: white;"
                                                    title="Restore Item">
                                                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                        <form id="delete-form-{{ $item->deployedID ?? $item->id }}" action="{{ route('deployed-items.force-delete', ['id' => $item->deployedID ?? $item->id]) }}" method="POST" class="inline mx-1">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="button" 
                                                    onclick="confirmForceDelete('{{ addslashes($itemName) }}', this)" 
                                                    class="btn btn-sm w-8 h-8 flex items-center justify-center p-0" 
                                                    style="background-color: #800000; border-color: #800000; color: white;"
                                                    title="Permanently Delete">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($deployedItems->hasPages())
                <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-5">
                    {{ $deployedItems->withQueryString()->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
// Handle form submission response
@if(session('success'))
    Swal.fire({
        title: 'Success',
        html: `
            <div class="text-center py-2">
                <i data-lucide="check-circle" class="w-10 h-10 mx-auto text-green-500 mb-2"></i>
                <p class="text-sm text-gray-600">
                    {{ session('success') }}
                </p>
            </div>
        `,
        showConfirmButton: true,
        confirmButtonText: 'OK',
        customClass: {
            confirmButton: 'btn btn-sm btn-warning px-4 py-1 text-xs',
            popup: 'text-sm',
            actions: 'mt-3'
        },
        buttonsStyling: false,
        width: '20rem',
        padding: '1rem'
    });
@endif

function confirmRestore(name, button) {
    const form = button.closest('form');
    
    Swal.fire({
        title: 'Restore Item',
        html: `
            <div class="text-center py-2">
                <i data-lucide="rotate-ccw" class="w-10 h-10 mx-auto text-green-500 mb-2"></i>
                <p class="text-sm text-gray-600 mb-1">
                    Restore <span class="font-medium">${name}</span>?
                </p>
                <p class="text-xs text-gray-500">
                    This item will be moved back to active items.
                </p>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Restore',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        customClass: {
            confirmButton: 'btn btn-sm btn-success px-4 py-1 text-xs',
            cancelButton: 'btn btn-sm btn-outline-secondary px-4 py-1 text-xs mr-2',
            popup: 'text-sm',
            actions: 'mt-3'
        },
        buttonsStyling: false,
        width: '20rem',
        padding: '1rem',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return new Promise((resolve) => {
                const confirmButton = document.querySelector('.swal2-confirm');
                if (confirmButton) {
                    confirmButton.innerHTML = '<i class="animate-spin -ml-1 mr-1 h-3 w-3">↻</i> Restoring...';
                    confirmButton.disabled = true;
                }
                form.submit();
            });
        }
    });
}

function confirmForceDelete(name, button) {
    const form = button.closest('form');
    
    Swal.fire({
        title: 'Permanently Delete',
        html: `
            <div class="text-center py-2">
                <i data-lucide="trash-2" class="w-10 h-10 mx-auto text-red-500 mb-2"></i>
                <p class="text-sm text-gray-600 mb-1">
                    Delete <span class="font-medium">${name}</span> permanently?
                </p>
                <p class="text-xs text-gray-500">
                    This action cannot be undone.
                </p>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Delete',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        customClass: {
            confirmButton: 'btn btn-sm btn-danger px-4 py-1 text-xs',
            cancelButton: 'btn btn-sm btn-outline-secondary px-4 py-1 text-xs mr-2',
            popup: 'text-sm',
            actions: 'mt-3'
        },
        buttonsStyling: false,
        width: '20rem',
        padding: '1rem',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return new Promise((resolve) => {
                const confirmButton = document.querySelector('.swal2-confirm');
                if (confirmButton) {
                    confirmButton.innerHTML = '<i class="animate-spin -ml-1 mr-1 h-3 w-3">↻</i> Deleting...';
                    confirmButton.disabled = true;
                }
                form.submit();
            });
        }
    });
}
</script>
@endpush
