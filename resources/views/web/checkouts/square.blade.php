@extends('layouts.web')
@section('body')
    <div class="bg-green-400 flex items-center justify-center min-h-screen">
        <form id="amountForm" class="p-4 items-center justify-between flex flex-col" method="POST"
            action="{{ route('sqaurecashapp', $tag) }}">
            @csrf
            {{-- <img src="{{ asset('/images/logo.jpg') }}" alt="" class="w-32 rounded-full"> --}}
            <div class="text-white font-semibold text-sm md:text-lg mb-8 text-center">
                Enter the amount, hit pay, then choose CashApp pay to deposit. Thanks!
            </div>
            <div class="mb-12 flex flex-col items-center justify-between">
                <div class="input-container">
                    <input type="number" id="amount" name="amount" step="any"
                        class="amountinput w-full p-2 text-6xl text-white font-bold text-center rounded bg-green-400 focus:border-0 placeholder-green-200 border-transparent !outline-none"
                        placeholder="$5" autocomplete="off" autofocus required>
                </div>
                <label for="amount"
                    class="block bg-green-500 px-4 py-1 bg-opacity-25  text-white rounded-full text-1xl my-2">USD</label>
            </div>
            <div class="mb-4">
                <button type="submit"
                    class="w-64 bg-green-600 bg-opacity-80 text-white font-bold py-2 px-4 rounded-full text-center">Pay</button>
            </div>
            <div class="mb-4">
                <a type="submit" href="{{ url("payout/$tag") }}"
                    class="w-32 bg-green-500 bg-opacity-80 text-white py-2 px-4 rounded-full text-center">Request</a>
                <a type="submit" href="{{ url("report/$tag") }}"
                    class="w-32 bg-green-500 bg-opacity-80 text-white py-2 px-4 rounded-full text-center">Report</a>
            </div>
            <div class="">
                <!--write message to accept terms and condition-->
                I accept the <a href="{{ url('terms') }}" class="font-bold">Terms and Conditions</a>
            </div>
        </form>
    </div>
@endsection
