@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- BEGIN: Analytics -->
<div class="grid grid-cols-12 gap-6">
    <div class="col-span-12">
        <div class="grid grid-cols-12 gap-6">
            <!-- Visitors Card -->
            <div class="col-span-12 sm:col-span-6">
                <div class="intro-y flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">
                        Visitors
                    </h2>
                    <a href="" class="ml-auto text-primary truncate">View on Map</a> 
                </div>
                <div class="report-box-2 intro-y mt-5">
                    <div class="box p-5 h-[500px]">
                        <div class="flex items-center">
                            Realtime active users 
                            <div class="dropdown ml-auto">
                                <a class="dropdown-toggle w-5 h-5 block -mr-2" href="javascript:;" aria-expanded="false" data-tw-toggle="dropdown"> 
                                    <i data-lucide="more-vertical" class="w-5 h-5 text-slate-500"></i>
                                </a>
                                <div class="dropdown-menu w-40">
                                    <ul class="dropdown-content">
                                        <li>
                                            <a href="" class="dropdown-item">
                                                <i data-lucide="file-text" class="w-4 h-4 mr-2"></i> Export
                                            </a>
                                        </li>
                                        <li>
                                            <a href="" class="dropdown-item">
                                                <i data-lucide="settings" class="w-4 h-4 mr-2"></i> Settings
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="text-2xl font-medium mt-2">214</div>
                        <div class="border-b border-slate-200 flex pb-2 mt-4">
                            <div class="text-slate-500 text-xs">Page views per second</div>
                            <div class="text-success flex text-xs font-medium tooltip cursor-pointer ml-auto">
                                49% <i data-lucide="chevron-up" class="w-4 h-4 ml-0.5"></i>
                            </div>
                        </div>
                        <div class="mt-2 border-b broder-slate-200">
                            <div class="-mb-1.5 -ml-2.5">
                                <div class="h-[79px]">
                                    <canvas id="report-bar-chart" width="194" height="79"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="text-slate-500 text-xs border-b border-slate-200 flex mb-2 pb-2 mt-4">
                            <div>Top Active Pages</div>
                            <div class="ml-auto">Active Users</div>
                        </div>
                        <div class="flex">
                            <div>/letz-lara…review/2653</div>
                            <div class="ml-auto">472</div>
                        </div>
                        <div class="flex mt-1.5">
                            <div>/midone…review/1674</div>
                            <div class="ml-auto">294</div>
                        </div>
                        <div class="flex mt-1.5">
                            <div>/profile…review/46789</div>
                            <div class="ml-auto">83</div>
                        </div>
                        <div class="flex mt-1.5">
                            <div>/profile…review/24357</div>
                            <div class="ml-auto">21</div>
                        </div>
                        <button class="btn btn-outline-secondary border-dashed w-full py-1 px-2 mt-4">Real-Time Report</button>
                    </div>
                </div>
            </div>

            <!-- Users By Age Card -->
            <div class="col-span-12 sm:col-span-6">
                <div class="intro-y flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">
                        Users By Age
                    </h2>
                    <a href="" class="ml-auto text-primary truncate">Show More</a> 
                </div>
                <div class="report-box-2 intro-y mt-5">
                    <div class="box p-5 h-[500px]">
                        <ul class="nav nav-pills w-4/5 bg-slate-100 dark:bg-black/20 rounded-md mx-auto" role="tablist">
                            <li id="active-users-tab" class="nav-item flex-1" role="presentation">
                                <button class="nav-link w-full py-1.5 px-2 active" data-tw-toggle="pill" data-tw-target="#active-users" type="button" role="tab" aria-controls="active-users" aria-selected="true">Active</button>
                            </li>
                            <li id="inactive-users-tab" class="nav-item flex-1" role="presentation">
                                <button class="nav-link w-full py-1.5 px-2" data-tw-toggle="pill" data-tw-target="#inactive-users" type="button" role="tab" aria-selected="false">Inactive</button>
                            </li>
                        </ul>
                        <div class="tab-content mt-6">
                            <div class="tab-pane active" id="active-users" role="tabpanel" aria-labelledby="active-users-tab">
                                <div class="relative">
                                    <div class="h-[208px]">
                                        <canvas class="mt-3" id="report-donut-chart" width="184" height="196"></canvas>
                                    </div>
                                    <div class="flex flex-col justify-center items-center absolute w-full h-full top-0 left-0">
                                        <div class="text-2xl font-medium">2.501</div>
                                        <div class="text-slate-500 mt-0.5">Active Users</div>
                                    </div>
                                </div>
                                <div class="w-52 sm:w-auto mx-auto mt-5">
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 bg-primary rounded-full mr-3"></div>
                                        <span class="truncate">17 - 30 Years old</span> <span class="font-medium ml-auto">62%</span> 
                                    </div>
                                    <div class="flex items-center mt-4">
                                        <div class="w-2 h-2 bg-pending rounded-full mr-3"></div>
                                        <span class="truncate">31 - 50 Years old</span> <span class="font-medium ml-auto">33%</span> 
                                    </div>
                                    <div class="flex items-center mt-4">
                                        <div class="w-2 h-2 bg-warning rounded-full mr-3"></div>
                                        <span class="truncate">&gt;= 50 Years old</span> <span class="font-medium ml-auto">10%</span> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: Analytics -->

<!-- BEGIN: Main Content -->
<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="col-span-12">
        <!-- BEGIN: General Report -->
        <div class="intro-y">
            <div class="flex items-center mt-8 mb-4">
                <h2 class="text-2xl font-medium truncate mr-5">General Report</h2>
            </div>
            <div class="grid grid-cols-12 gap-6">
                <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                    <div class="report-box zoom-in">
                        <div class="box p-5">
                            <div class="flex">
                                <i data-lucide="package" class="report-box__icon text-primary"></i>
                            </div>
                            <div class="text-3xl font-medium leading-8 mt-6">{{ $totalSupplies }}</div>
                            <div class="text-base text-slate-500 mt-1">Total Supplies</div>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                    <div class="report-box zoom-in">
                        <div class="box p-5">
                            <div class="flex">
                                <i data-lucide="alert-triangle" class="report-box__icon text-warning"></i>
                            </div>
                            <div class="text-3xl font-medium leading-8 mt-6">{{ $lowStockCount }}</div>
                            <div class="text-base text-slate-500 mt-1">Low Stock Items</div>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                    <div class="report-box zoom-in">
                        <div class="box p-5">
                            <div class="flex">
                                <i data-lucide="folder" class="report-box__icon text-success"></i>
                            </div>
                            <div class="text-3xl font-medium leading-8 mt-6">{{ $totalCategories }}</div>
                            <div class="text-base text-slate-500 mt-1">Categories</div>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                    <div class="report-box zoom-in">
                        <div class="box p-5">
                            <div class="flex">
                                <i data-lucide="repeat" class="report-box__icon text-info"></i>
                            </div>
                            <div class="text-3xl font-medium leading-8 mt-6">{{ $todayTransactions }}</div>
                            <div class="text-base text-slate-500 mt-1">Today's Transactions</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: General Report -->

        <!-- BEGIN: Recent Transactions -->
        <div class="intro-y col-span-12 mt-8">
            <div class="flex items-center h-10 mb-5">
                <h2 class="text-2xl font-medium truncate mr-5">Recent Transactions</h2>
                <a href="{{ route('transactions.index') }}" class="ml-auto btn btn-primary shadow-md">View All</a>
            </div>
            <div class="box p-5">
                <div class="overflow-x-auto">
                    <table class="table table-report">
                        <thead>
                            <tr>
                                <th class="whitespace-nowrap">Supply</th>
                                <th class="whitespace-nowrap">Type</th>
                                <th class="text-center whitespace-nowrap">Quantity</th>
                                <th class="text-center whitespace-nowrap">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransactions as $transaction)
                            <tr class="intro-x">
                                <td class="w-40 !py-4">{{ $transaction->supply?->name ?? 'Deleted Supply' }}</td>
                                <td class="!py-4">
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
                                <td class="text-center !py-4">{{ $transaction->quantity }}</td>
                                <td class="text-center !py-4">{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                            @empty
                            <tr class="intro-x">
                                <td colspan="4" class="text-center !py-4">No transactions found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- END: Recent Transactions -->

        <!-- BEGIN: Low Stock Alerts -->
        <div class="intro-y col-span-12 mt-8">
            <div class="flex items-center h-10 mb-5">
                <h2 class="text-2xl font-medium truncate mr-5">Low Stock Alerts</h2>
                <a href="{{ route('supplies.index') }}" class="ml-auto btn btn-primary shadow-md">View All Supplies</a>
            </div>
            <div class="box p-5">
                <div class="overflow-x-auto">
                    <table class="table table-report">
                        <thead>
                            <tr>
                                <th class="whitespace-nowrap">Supply</th>
                                <th class="text-center whitespace-nowrap">Current Stock</th>
                                <th class="text-center whitespace-nowrap">Minimum Stock</th>
                                <th class="text-center whitespace-nowrap">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lowStockItems as $supply)
                            <tr class="intro-x">
                                <td class="w-40 !py-4">{{ $supply->name }}</td>
                                <td class="text-center !py-4">{{ $supply->quantity }}</td>
                                <td class="text-center !py-4">{{ $supply->minimum_stock }}</td>
                                <td class="text-center !py-4">
                                    <div class="flex items-center justify-center text-danger">
                                        <i data-lucide="alert-triangle" class="w-4 h-4 mr-1"></i> Low Stock
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr class="intro-x">
                                <td colspan="4" class="text-center !py-4">No low stock items found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- END: Low Stock Alerts -->
    </div>
</div>
<!-- END: Main Content -->
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Lucide icons
    lucide.createIcons();

    // Report Bar Chart
    const reportBarCtx = document.getElementById('report-bar-chart').getContext('2d');
    new Chart(reportBarCtx, {
        type: 'bar',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Page Views',
                data: [150, 230, 180, 290, 200, 250, 300],
                backgroundColor: '#0ea5e9',
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        display: false
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Report Donut Chart
    const reportDonutCtx = document.getElementById('report-donut-chart').getContext('2d');
    new Chart(reportDonutCtx, {
        type: 'doughnut',
        data: {
            labels: ['17-30', '31-50', '50+'],
            datasets: [{
                data: [62, 33, 10],
                backgroundColor: ['#0ea5e9', '#f59e0b', '#f97316'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            cutout: '80%'
        }
    });
});
</script>
@endpush
