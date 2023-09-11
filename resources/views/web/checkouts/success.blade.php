@extends('layouts.web')
@section('body')
    <div class="bg-green-500 flex items-center justify-center min-h-screen">
        <div class="p-10 rounded-lg text-center">
            <div class="mb-5 text-green-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 mx-auto" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold mb-5">Payment Successful</h1>
            <p class="text-gray-700 mb-5">Thank you for your purchase! Your transaction has been completed.</p>
        </div>
    </div>
@endsection
