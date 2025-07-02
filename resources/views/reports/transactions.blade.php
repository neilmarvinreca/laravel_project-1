@extends('layouts.app')

@section('title', 'Transactions Report')

@section('content')
<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Transactions Report</h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
        <a href="{{ route('reports.index') }}" class="btn btn-secondary shadow-md mr-2">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to Reports
        </a>
        <form action="{{ route('reports.export', ['type' => 'transactions']) }}" method="GET" class="inline-block">
            <button type="submit" class="btn btn-success shadow-md">
                <i data-lucide="file-text" class="w-4 h-4 mr-2"></i> Export Report
            </button>
        </form>
    </div>
</div>

<!-- BEGIN: Transactions Report -->
<div class="intro-y box p-5 mt-5">
    <div class="overflow-x-auto">
        <table class="table table-report -mt-2">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">Date</th>
                    <th class="whitespace-nowrap">Item Name</th>
                    <th class="whitespace-nowrap">Type</th>
                    <th class="whitespace-nowrap">Quantity</th>
                    <th class="whitespace-nowrap">Unit</th>
                    <th class="whitespace-nowrap">Remarks</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $transaction)
                    <tr class="intro-x">
                        <td>{{ $transaction->created_at->format('M d, Y h:i A') }}</td>
                        <td>{{ $transaction->supply?->name ?? 'Deleted Item' }}</td>
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
                        <td>{{ $transaction->supply?->unit ?? 'N/A' }}</td>
                        <td>{{ $transaction->remarks }}</td>
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
    @if(method_exists($transactions, 'links'))
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-5">
            {{ $transactions->links() }}
        </div>
    @endif
    <!-- END: Pagination -->
</div>
<!-- END: Transactions Report -->
@endsection
