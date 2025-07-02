@extends('layouts.app')

@section('title', 'View Supply')

@section('content')
<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Supply Details</h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
        <a href="{{ route('supplies.index') }}" class="btn btn-secondary shadow-md mr-2">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to Supplies
        </a>
        <a href="{{ route('supplies.edit', $supply) }}" class="btn btn-primary shadow-md">
            <i data-lucide="edit" class="w-4 h-4 mr-2"></i> Edit Supply
        </a>
    </div>
</div>

<!-- BEGIN: Supply Details -->
<div class="intro-y box p-5 mt-5">
    <div class="grid grid-cols-12 gap-6">
        <!-- Basic Information -->
        <div class="col-span-12 xl:col-span-6">
            <div class="box p-5 rounded-md bg-slate-50 dark:bg-darkmode-600">
                <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                    <div class="font-medium text-base truncate">Basic Information</div>
                </div>
                <div class="grid grid-cols-12 gap-4">
                    <div class="col-span-4 font-medium">Name:</div>
                    <div class="col-span-8">{{ $supply->name }}</div>

                    <div class="col-span-4 font-medium">Category:</div>
                    <div class="col-span-8">{{ $supply->category->name }}</div>

                    <div class="col-span-4 font-medium">Description:</div>
                    <div class="col-span-8">{{ $supply->description ?? 'N/A' }}</div>
                </div>
            </div>
        </div>

        <!-- Stock Information -->
        <div class="col-span-12 xl:col-span-6">
            <div class="box p-5 rounded-md bg-slate-50 dark:bg-darkmode-600">
                <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                    <div class="font-medium text-base truncate">Stock Information</div>
                </div>
                <div class="grid grid-cols-12 gap-4">
                    <div class="col-span-4 font-medium">Current Stock:</div>
                    <div class="col-span-8">{{ $supply->quantity }} {{ $supply->unit }}</div>

                    <div class="col-span-4 font-medium">Minimum Quantity:</div>
                    <div class="col-span-8">{{ $supply->minimum_quantity }} {{ $supply->unit }}</div>

                    <div class="col-span-4 font-medium">Status:</div>
                    <div class="col-span-8">
                        @if($supply->quantity <= $supply->minimum_quantity)
                            <div class="flex items-center text-danger">
                                <i data-lucide="alert-circle" class="w-4 h-4 mr-1"></i> Low Stock
                            </div>
                        @else
                            <div class="flex items-center text-success">
                                <i data-lucide="check-circle" class="w-4 h-4 mr-1"></i> In Stock
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="col-span-12">
            <div class="box p-5 rounded-md">
                <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                    <div class="font-medium text-base truncate">Recent Transactions</div>
                </div>
                <div class="overflow-x-auto">
                    <table class="table table-report -mt-2">
                        <thead>
                            <tr>
                                <th class="whitespace-nowrap">Date</th>
                                <th class="whitespace-nowrap">Type</th>
                                <th class="whitespace-nowrap">Quantity</th>
                                <th class="whitespace-nowrap">Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($supply->transactions()->latest()->take(5)->get() as $transaction)
                                <tr class="intro-x">
                                    <td>{{ $transaction->created_at->format('M d, Y h:i A') }}</td>
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
                                    <td>{{ $transaction->quantity }} {{ $supply->unit }}</td>
                                    <td>{{ $transaction->remarks }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No transactions found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: Supply Details -->
@endsection
