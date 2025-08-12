@extends('layouts.app')

@section('title', 'View Supply')

@section('content')
<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Supply Details
    </h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
        <a href="{{ route('supplies.index') }}" class="btn btn-secondary shadow-md">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to Supplies
        </a>
    </div>
</div>

<!-- BEGIN: Supply Details -->
<div class="intro-y box p-5 mt-5">
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            <div class="border-b border-gray-200 dark:border-dark-5 pb-5">
                <div class="mb-4">
                    <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 mb-1">Item ID:</h3>
                    <p class="font-medium">#{{ str_pad($supply->itemID, 4, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div class="mb-4">
                    <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 mb-1">Name:</h3>
                    <p class="font-medium">{{ $supply->name }}</p>
                </div>
                <div class="mb-4">
                    <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 mb-1">Category:</h3>
                    <p class="font-medium">{{ $supply->category->categoryName ?? 'N/A' }}</p>
                </div>
                <div class="mb-4">
                    <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 mb-1">Description:</h3>
                    <p class="font-medium">{{ $supply->description ?? 'N/A' }}</p>
                </div>
                <div class="mb-4">
                    <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 mb-1">Department:</h3>
                    <p class="font-medium">
                        {{ $supply->department ? $supply->department->officename . ' (' . $supply->department->departmentID . ')' : 'N/A' }}
                    </p>
                </div>
                <div class="mb-4">
                    <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 mb-1">Quantity:</h3>
                    <p class="font-medium">{{ $supply->quantity }}</p>
                </div>
                <div class="mb-4">
                    <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 mb-1">Status:</h3>
                    @if($supply->quantity <= 5)
                        <p class="flex items-center text-danger">
                            <i data-lucide="alert-circle" class="w-4 h-4 mr-1"></i>
                            Low Stock ({{ $supply->quantity }} left)
                        </p>
                    @else
                        <p class="flex items-center text-success">
                            <i data-lucide="check-circle" class="w-4 h-4 mr-1"></i>
                            In Stock ({{ $supply->quantity }} available)
                        </p>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-span-12 md:col-span-6">
            <div class="box p-5">
                <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 mb-4">Financial Information</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Unit Cost:</span>
                        <div class="text-right">
                            <div class="font-medium">₱{{ number_format($supply->unit_cost, 2) }}</div>
                            <div class="text-xs text-gray-500">{{ $supply->quantity }} units × ₱{{ number_format($supply->unit_cost, 2) }}</div>
                        </div>
                    </div>
                    <div class="border-t border-gray-200 my-2"></div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 font-semibold">Total Amount:</span>
                        <div class="text-right">
                            <div class="font-bold text-lg text-primary">₱{{ number_format($supply->amount, 2) }}</div>
                        </div>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Fund Code:</span>
                        <span class="font-medium">{{ $supply->fund_code }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">PPE Sub Account:</span>
                        <span class="font-medium">{{ $supply->pp_sub_account }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">GL Code:</span>
                        <span class="font-medium">{{ $supply->gl_code }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-span-12 md:col-span-6">
            <div class="box p-5">
                <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 mb-4">Additional Information</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Acquired At:</span>
                        <span class="font-medium">{{ $supply->acquired_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Estimated Life:</span>
                        <span class="font-medium">{{ $supply->estimated_life ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Added By:</span>
                        <span class="font-medium">{{ $supply->addedBy->name ?? 'System' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Date Added:</span>
                        <span class="font-medium">{{ $supply->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Last Updated:</span>
                        <span class="font-medium">{{ $supply->updated_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>
<!-- END: Supply Details -->
@endsection
