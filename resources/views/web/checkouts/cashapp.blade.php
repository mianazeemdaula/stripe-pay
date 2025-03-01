@extends('layouts.web')
@section('body')
    <div class="bg-green-400 flex items-center justify-center min-h-screen">
        <form id="amountForm" class="p-4 items-center justify-between flex flex-col">
            {{-- <img src="{{ asset('/images/logo.jpg') }}" alt="" class="w-32 rounded-full"> --}}
            <div class="text-white font-semibold text-sm md:text-lg mb-8 text-center">
                Enter the amount, hit pay, then choose your pay method. Thanks!
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

@section('js')
    <script src="https://js.stripe.com/v3/"></script>

    <script type="text/javascript">
        var stripe = Stripe(
            "{{ env('STRIPE_KEY') }}"
        );
        document.getElementById('amountForm').addEventListener('submit', function(e) {
            e.preventDefault();
            let amount = document.getElementById('amount').value;
            fetch("/cashapp-session", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        amount: amount,
                        _token: '{{ csrf_token() }}',
                        'tag': '{{ $tag }}',
                    })
                })
                .then(function(response) {
                    return response.json();
                })
                .then(function(sessionId) {
                    return stripe.redirectToCheckout({
                        sessionId: sessionId,
                    });
                })
                .then(function(result) {
                    if (result.error) {
                        alert(result.error.message);
                    }
                })
                .catch(function(error) {
                    console.error('Error:', error);
                });
        });

        function addPrefix(input) {
            // Add your desired prefix, such as "$"
            var prefix = "$";
            // Remove any existing prefixes to avoid duplication
            input.value = input.value.replace(prefix, '');
            // Add the prefix to the input value
            input.value = prefix + input.value;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('amount');
            input.focus(); // Focus on the input field
        });
    </script>
@endsection
