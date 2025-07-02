@extends('layouts.app')

@section('title', 'Inventory')

@section('content')
    <div class="mx-auto max-w-7xl">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">Inventory</h2>
            <a href="{{ route('supplies.create') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                Add Supply
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                    <thead class="bg-gray-50 dark:bg-gray-700 text-xs uppercase text-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Name</th>
                            <th scope="col" class="px-6 py-3">Category</th>
                            <th scope="col" class="px-6 py-3">Quantity</th>
                            <th scope="col" class="px-6 py-3">Unit</th>
                            <th scope="col" class="px-6 py-3">Location</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($supplies as $supply)
                            <tr class="border-b dark:border-gray-700">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                    {{ $supply->name }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $supply->category->name }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $supply->quantity }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $supply->unit }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $supply->location }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($supply->quantity <= $supply->minimum_stock)
                                        <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">
                                            Low Stock
                                        </span>
                                    @else
                                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">
                                            In Stock
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex space-x-3">
                                        <a href="{{ route('supplies.edit', $supply) }}" class="text-primary hover:text-primary-dark">
                                            <i data-lucide="edit" class="w-4 h-4"></i>
                                        </a>
                                        <a href="{{ route('supplies.show', $supply) }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    No supplies found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4">
                {{ $supplies->links() }}
            </div>
        </div>
    </div>
@endsection 