@extends('layouts.admin')

@section('body')
    <div class="flex space-x-2 items-center justify-between">
        <div class="flex items-center space-x-2">
            <span class="bi bi-gear text-2xl"></span>
            <h2 class="text-xl">Payments</h2>
        </div>
        <div>
            <a href="{{ route('user.payments.create') }}" class="p-2 bg-green-500 text-white rounded-md">Add Payment</a>
        </div>
    </div>
    <div class="mt-4 bg-white">
        <div class="bg-green-500  p-2 flex justify-between">
            <h2 class="text-white">Payments</h2>
            <div class="text-white">USD {{ auth()->user()->transaction->balance ?? '0' }}</div>
            {{-- <a class="p-2 bg-white rounded-md text-xs" href="{{ route('user.invoices.create') }}">Add Invoie</a> --}}
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
                                Note</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Amount Paid</th>

                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tax
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Received
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Time</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($collection as $item)
                            <tr class="border-b-2">
                                <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-500">
                                    <span class="{{ $item->gateway->logo }}">
                                        @if (!$item->gateways->logo)
                                            {{ $item->gateway->name }}
                                        @endif
                                    </span>
                                    {{ $item->id }}
                                </td>
                                <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-500">
                                </td>
                                <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-500">
                                    {{ $item->status }}
                                </td>
                                <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-500">
                                    ${{ $item->amount }}
                                </td>

                                <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-500">
                                    ${{ $item->tax }}
                                </td>
                                <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-500">
                                    ${{ $item->amount - $item->tax }}
                                </td>
                                <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-500">
                                    {{ $item->created_at }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    {{-- pagniation --}}
    <div class="mt-4">
        {{ $collection->links() }}
    </div>
@endsection
