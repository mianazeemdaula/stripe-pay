@extends('layouts.web')

@section('body')
<div class="flex h-screen">
    <div class="flex-1 md:block hidden py-12 px-12 md:px-32">
        <div class="flex items-center justify-center">
            <img src="{{ asset('/images/logo.jpg') }}" alt="" class="w-40 rounded-full">
        </div>
        <div class="mt-12">
            <h1 class="text-2xl font-bold text-center">Request Money</h1>
            <p class="text-center mt-4">Request money from Seller with a CashTag.</p>
        </div>
    </div>
    <div class="flex-1 shadow-xl py-12 px-12 md:px-32">

        <form action="{{ url("payout/$tag") }}" method="post">
            @csrf
            @if(session('success'))  <div class="mb-4 bg-green-500 text-white p-2 rounded-md">{{ session('success') }}</div> @endif
            @if ($errors->any())
                <div class="col-sm-12">
                    <div class="alert  alert-warning alert-dismissible fade show" role="alert">
                        @foreach ($errors->all() as $error)
                            <span><p>{{ $error }}</p></span>
                        @endforeach
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                </div>
            @endif
            <div class="mb-4">
                <label for="amount" class="block text-gray-700 text-sm font-bold mb-2">Request Amount</label>
                <input type="number" step="any" name="amount" id="amount" class="w-full p-2 border-2 rounded-md" placeholder="$5" autocomplete="off" autofocus required>
                @error('amount')<p class="text-red-500 text-xs mt-2">{{ $message }}</p>@enderror
            </div>
            <div class="mb-4">
                <label for="cashtag" class="block text-gray-700 text-sm font-bold mb-2">CashTag</label>
                <input type="text" name="cashtag" id="cashtag" class="w-full p-2 border-2 rounded-md" placeholder="$cashtag" autocomplete="off" autofocus required>
                @error('cashtag')<p class="text-red-500 text-xs mt-2">{{ $message }}</p>@enderror
            </div>
            <div class="mb-4">
                <label for="note" class="block text-gray-700 text-sm font-bold mb-2">Note</label>
                <input type="text" name="note" id="note" class="w-full p-2 border-2 rounded-md" placeholder="Note" autocomplete="off">
            </div>
            <div class="mb-4">
                <button class="w-full bg-green-500 font-bold text-white rounded-md h-12 hover:bg-green-400">Send Request</button>
            </div>
            <div>
                <!--write message to accept terms and condition-->
                I accept the <a href="{{ url('terms') }}" class="text-green-500">Terms and Conditions</a>
            </div>
        </form>
    </div>
</div>
{{-- <div class="flex items-center justify-center h-screen">
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
</div> --}}
@endsection