@extends('layouts.app')

<link href="{{ asset('dist/images/logodssc.png') }}" rel="shortcut icon">
@section('title', 'Reports')
@section('content')
<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Reports Dashboard</h2>
</div>

<div class="grid grid-cols-12 gap-6 mt-5">
    <!-- BEGIN: Inventory Report Card -->
    <div class="col-span-12 sm:col-span-6 xl:col-span-4 intro-y">
        <div class="report-box zoom-in">
            <div class="box p-5">
                <div class="flex">
                    <i data-lucide="bar-chart-2" class="report-box__icon text-primary"></i>
                </div>
                <div class="text-base font-medium leading-8 mt-6">Inventory Report</div>
                <div class="text-slate-500 mt-1">View current stock levels and inventory status</div>
                <a href="{{ route('reports.inventory') }}" class="btn btn-primary mt-4 w-full">
                    View Report
                </a>
            </div>
        </div>
    </div>
    <!-- END: Inventory Report Card -->

    <!-- BEGIN: Deployed Items Report Card -->
    <div class="col-span-12 sm:col-span-6 xl:col-span-4 intro-y">
        <div class="report-box zoom-in">
            <div class="box p-5">
                <div class="flex">
                    <i data-lucide="package-check" class="report-box__icon text-success"></i>
                </div>
                <div class="text-base font-medium leading-8 mt-6">Deployed Items Report</div>
                <div class="text-slate-500 mt-1">View all deployed items and their status</div>
                <a href="{{ route('reports.deployed-items') }}" class="btn btn-primary mt-4 w-full">
                    View Report
                </a>
            </div>
        </div>
    </div>
    <!-- END: Deployed Items Report Card -->

    <!-- BEGIN: Low Stock Report Card -->
    <div class="col-span-12 sm:col-span-6 xl:col-span-4 intro-y">
        <div class="report-box zoom-in">
            <div class="box p-5">
                <div class="flex">
                    <i data-lucide="alert-triangle" class="report-box__icon text-warning"></i>
                </div>
                <div class="text-base font-medium leading-8 mt-6">Low Stock Report</div>
                <div class="text-slate-500 mt-1">View items that need restocking</div>
                <a href="{{ route('reports.low-stock') }}" class="btn btn-primary mt-4 w-full">
                    View Report
                </a>
            </div>
        </div>
    </div>
    <!-- END: Low Stock Report Card -->
</div>

<!-- BEGIN: Export Reports Section -->
<div class="intro-y box mt-5">
    <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
        <h2 class="font-medium text-base mr-auto">Export Reports</h2>
    </div>
    <div class="p-5">
        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12 sm:col-span-6 xl:col-span-4">
                <form action="{{ route('reports.export', ['type' => 'inventory']) }}" method="GET">
                    <button type="submit" class="btn btn-primary w-full py-2.5 text-sm">
                        <i data-lucide="file-text" class="w-4 h-4 mr-2"></i> Export Inventory Report
                    </button>
                </form>
            </div>
            <div class="col-span-12 sm:col-span-6 xl:col-span-4">
                <form action="{{ route('reports.export', ['type' => 'deployed-items']) }}" method="GET">
                    <button type="submit" class="btn btn-primary w-full py-2.5 text-sm">
                        <i data-lucide="file-text" class="w-4 h-4 mr-2"></i> Export Deployed Items Report
                    </button>
                </form>
            </div>
            <div class="col-span-12 sm:col-span-6 xl:col-span-4">
                <form action="{{ route('reports.export', ['type' => 'low-stock']) }}" method="GET">
                    <button type="submit" class="btn btn-primary w-full py-2.5 text-sm">
                        <i data-lucide="file-text" class="w-4 h-4 mr-2"></i> Export Low Stock Report
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- END: Export Reports Section -->
@endsection
