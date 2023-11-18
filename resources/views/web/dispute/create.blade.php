@extends('layouts.web')

@section('body')
<div class="flex h-screen">
    <div class="flex-1 md:block hidden py-12 px-12 md:px-32">
        <div class="flex items-center justify-center">
            <img src="{{ asset('/images/logo.jpg') }}" alt="" class="w-40 rounded-full">
        </div>
        <div class="mt-12">
            <h1 class="text-2xl font-bold text-center">Open Dispute</h1>
            <p class="text-center mt-4">If you have encountered any issues or discrepancies with our service, product, or any transaction, we encourage you to submit a dispute. Your satisfaction is our top priority, and we are committed to resolving any concerns you may have.</p>
        </div>
    </div>
    <div class="flex-1 shadow-xl py-12 px-12 md:px-32">

        <form action="{{ url("report/$tag") }}" method="post">
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
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input type="email" value="{{ old('email') }}" name="email" id="email" class="w-full p-2 border-2 rounded-md" placeholder="abc@mail.com" autocomplete="off" autofocus required>
                @error('email')<p class="text-red-500 text-xs mt-2">{{ $message }}</p>@enderror
            </div>
            <div class="mb-4">
                <label for="reason" class="block text-gray-700 text-sm font-bold mb-2">Reason</label>
                <textarea name="reason" id="" class="w-full p-2 border-2 rounded-md" cols="30" rows="10">{{old('reason')}}</textarea>
                @error('reason')<p class="text-red-500 text-xs mt-2">{{ $message }}</p>@enderror
            </div>
            <div class="mb-4">
                <button class="w-full bg-green-500 font-bold text-white rounded-md h-12 hover:bg-green-400">Open Dispute</button>
            </div>
            <div>
                <!--write message to accept terms and condition-->
                I accept the <a href="{{ url('terms') }}" class="text-green-500">Terms and Conditions</a>
            </div>
        </form>
    </div>
</div>
@endsection