@extends('layouts.web')
@section('body')
    <div class="bg-green-400 flex items-center justify-center min-h-screen">
        <form id="amountForm" class="p-6 items-center justify-between flex flex-col">
            <div class="mb-8 flex flex-col items-center justify-between">
                <input type="number" id="amount" name="amount"
                    class="w-full p-2 text-6xl text-white font-bold text-center rounded bg-green-400 focus:border-0 placeholder-white border-transparent !outline-none
                    "
                    placeholder="$5" autofocus required>
                <label for="amount"
                    class="block bg-green-500 px-4 py-1 bg-opacity-25  text-white rounded-full text-1xl my-2">USD</label>
            </div>
            <button type="submit" class="bg-green-500 bg-opacity-80 text-white py-2 px-4 rounded-full">Pay with
                CashApp</button>
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
    </script>
@endsection
