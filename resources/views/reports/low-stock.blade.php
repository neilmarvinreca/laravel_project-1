@extends('layouts.app')

@section('title', 'Low Stock Report')

@section('content')
<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Low Stock Report</h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
        <a href="{{ route('reports.index') }}" class="btn btn-secondary shadow-md mr-2">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to Reports
        </a>
        <form action="{{ route('reports.export', ['type' => 'low-stock']) }}" method="GET" class="inline-block">
            <button type="submit" class="btn btn-success shadow-md">
                <i data-lucide="file-text" class="w-4 h-4 mr-2"></i> Export Report
            </button>
        </form>
    </div>
</div>

<!-- BEGIN: Low Stock Report -->
<div class="intro-y box p-5 mt-5">
    <div class="overflow-x-auto">
        <table class="table table-report -mt-2">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">Item Name</th>
                    <th class="whitespace-nowrap">Category</th>
                    <th class="whitespace-nowrap">Current Stock</th>
                    <th class="whitespace-nowrap">Minimum Quantity</th>
                    <th class="whitespace-nowrap">Unit</th>
                    <th class="whitespace-nowrap">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($supplies as $supply)
                    <tr class="intro-x">
                        <td>{{ $supply->name }}</td>
                        <td>{{ $supply->category->name }}</td>
                        <td>{{ $supply->quantity }}</td>
                        <td>{{ $supply->minimum_quantity }}</td>
                        <td>{{ $supply->unit }}</td>
                        <td>
                            @if($supply->quantity <= $supply->minimum_quantity)
                                <div class="flex items-center text-danger">
                                    <i data-lucide="alert-circle" class="w-4 h-4 mr-1"></i> Low Stock
                                </div>
                            @else
                                <div class="flex items-center text-success">
                                    <i data-lucide="check-circle" class="w-4 h-4 mr-1"></i> In Stock
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No low stock items found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- BEGIN: Pagination -->
    @if(method_exists($supplies, 'links'))
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-5">
            {{ $supplies->links() }}
        </div>
    @endif
    <!-- END: Pagination -->
</div>
<!-- END: Low Stock Report -->
@endsection
