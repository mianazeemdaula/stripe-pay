@extends('layouts.admin')

@section('body')
    <div class="flex space-x-2 items-center">
        <span class="bi bi-gear text-2xl"></span>
        <h2 class="text-xl">Products</h2>
    </div>
    <div class="mt-4 bg-white">
        <div class="bg-green-500  p-2 flex justify-between">
            <h2 class="text-white">Products</h2>
            <a class="p-2 bg-white rounded-md text-xs" href="{{ route('user.products.create') }}">Add Product</a>
        </div>
        <div class="px-4 pb-2">
            <div class="overflow-x-auto mt-2">
                <table class="min-w-full divide-y divide-gray-200 table-striped table-bordered" id="dataTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ID</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Name</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Description</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($collection as $item)
                            <tr class="border-b-2">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $item->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $item->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $item->description }}
                                </td>
                                <td class="flex space-x-1">
                                    <a href="{{ route('user.products.show', $item->id) }}"><span
                                            class="bi bi-eye"></span></a>
                                    <form action="{{ route('user.products.update', $item->id) }}" method="post">
                                        @method('put')
                                        @csrf
                                        <input type="hidden" name="status" value="accept">
                                        <button type="submit"><span class="bi bi-check"></span></button>
                                    </form>
                                    <form action="{{ route('user.products.update', $item->id) }}" method="post">
                                        @method('put')
                                        @csrf
                                        <input type="hidden" name="status" value="REJECTED">
                                        <button type="submit"><span class="bi bi-x"></span></button>
                                    </form>
                                    {{-- <form action="{{ route('sells.destroy', $item->id) }}" method="post">
                                    @method('delete')
                                    @csrf
                                    <button type="submit"><span class="bi bi-trash"></span></button>
                                </form> --}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
