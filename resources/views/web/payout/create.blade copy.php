@extends('layouts.web')

@section('body')
<div class="flex items-center justify-center h-screen">
    <div class=" shadow-2xl m-4 bg-green-500 rounded-lg">
        <form id="amountForm" class="p-6 items-center justify-between flex flex-col">
            @csrf
            <div class="mb-8 flex flex-col items-center justify-between">
                <input type="number" id="amount" name="amount"
                    class="w-full p-2 font-bold rounded border"
                    placeholder="$5" autocomplete="off"  autofocus required >
                <label for="amount"
                    class="block bg-green-500 px-4 py-1 bg-opacity-25  text-white rounded-full text-1xl my-2">USD</label>
            </div>
            <div>
                <input type="text" name="account" class="w-full p-2 font-bold rounde border" id="" placeholder="$cashtag">
            </div>
            <div class="mt-4">
                <button type="submit" class="bg-green-800 font-bold bg-opacity-80 text-white py-2 px-4 rounded-full text-center">Send Request</button>
            </div>
        </form>
    </div>
</div>
@endsection