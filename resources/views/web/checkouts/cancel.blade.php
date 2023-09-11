@extends('layouts.web')
@section('body')
    <div class="bg-red-300 flex items-center justify-center min-h-screen">
        <div class="p-10 rounded-lg text-center">
            <div class="mb-5 text-red-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 mx-auto" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold mb-5">Payment Cancelled</h1>
            <p class="text-gray-700 mb-5">Your transaction has been cancelled. If you have any questions or concerns, please
                contact our support team.</p>
        </div>
    </div>
@endsection
