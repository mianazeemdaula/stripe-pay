@extends('layouts.web')
@section('body')
    <div class="h-screen flex items-center justify-center ">
        <form action="{{ url('login') }}" method="post">
            @csrf
            <div class="mt-4 bg-white shadow">
                <div class="bg-green-500  p-4">
                    <h2 class="text-white">Login</h2>
                </div>
                @if ($errors->any())
                    {!! implode('', $errors->all('<p class="text-red-500 text-xs italic">{{ $message }}</p>')) !!}
                @endif
                <div class="grid grid-cols-1 md:grid-cols-1 p-4 gap-2 md:p-8">
                    <div>
                        <h3 class="p-1">Email</h3>
                        <input type="text" placeholder="Email" name="email" value="{{ old('email') }}"
                            class="w-80 p-2 rounded border">
                        @error('email')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <h3 class="p-1">Password</h3>
                        <input type="password" placeholder="Password" name="password" value="{{ old('password') }}"
                            class="w-80 p-2 rounded border">
                        @error('password')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="px-6 py-2 flex justify-end">
                    <button type="submit" class="bg-green-500 py-2 rounded px-8 text-white">Login</button>
                </div>
            </div>
        </form>
    </div>
@endsection
