@extends('layouts.app')

@section('title', 'Supplies')

@section('content')
<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Supplies Management</h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
        <a href="{{ route('supplies.create') }}" class="btn btn-primary shadow-md mr-2">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Add New Supply
        </a>
    </div>
</div>

<!-- BEGIN: Supplies List -->
<div class="intro-y box p-5 mt-5">
    <div class="overflow-x-auto">
        <table class="table table-report -mt-2">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">Name</th>
                    <th class="whitespace-nowrap">Category</th>
                    <th class="whitespace-nowrap">Quantity</th>
                    <th class="whitespace-nowrap">Unit</th>
                    <th class="whitespace-nowrap">Status</th>
                    <th class="whitespace-nowrap text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($supplies as $supply)
                    <tr class="intro-x">
                        <td>{{ $supply->name }}</td>
                        <td>{{ $supply->category->name }}</td>
                        <td>{{ $supply->quantity }}</td>
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
                        <td class="table-report__action w-56">
                            <div class="flex justify-center items-center">
                                <a href="{{ route('supplies.show', $supply) }}" class="btn btn-sm btn-primary mr-2">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                                <a href="{{ route('supplies.edit', $supply) }}" class="btn btn-sm btn-warning mr-2">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('supplies.destroy', $supply) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this supply?');">
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
                        <td colspan="6" class="text-center">No supplies found.</td>
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
<!-- END: Supplies List -->
@endsection
