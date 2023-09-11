@extends('layouts.web')
@section('body')
    <div>
        <form id="amountForm" class="bg-white p-6 rounded shadow-md">
            <div class="mb-4">
                <label for="amount" class="block text-gray-700">Amount:</label>
                <input type="text" id="amount" name="amount" class="w-full p-2 border border-gray-300 rounded mt-1"
                    required>
            </div>
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Submit</button>
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
            fetch('/checkout', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        amount: amount,
                        _token: '{{ csrf_token() }}',
                        'invoice_id': '{{ $id }}',
                    })
                })
                .then(function(response) {
                    return response.json();
                })
                .then(function(response) {
                    return stripe.redirectToCheckout({
                        sessionId: response.id,
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
