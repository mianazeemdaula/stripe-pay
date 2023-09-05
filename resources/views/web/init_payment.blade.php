<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Payout</title>
    <style>
        body {
            font-family: 'Arial, sans-serif';
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .checkout-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .checkout-container h1 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #333;
        }

        .checkout-container p {
            font-size: 18px;
            margin-bottom: 20px;
            color: #666;
        }

        .checkout-container .price {
            font-size: 24px;
            margin-bottom: 20px;
            color: #00a859;
            font-weight: bold;
        }

        .checkout-container .checkout-button {
            background-color: #00a859;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .checkout-container .checkout-button:hover {
            background-color: #008d4d;
        }
    </style>

    <script src="https://js.stripe.com/v3/"></script>
</head>

<body>

    <div class="checkout-container">
        <h1>Exclusive Offer</h1>
        <p>Get the best product at an unbeatable price.</p>
        <div class="price">$ 20</div>
        <button class="checkout-button">Checkout</button>
    </div>

    <script type="text/javascript">
        var stripe = Stripe(
            "{{ env('STRIPE_KEY') }}"
        );
        var checkoutButton = document.getElementById('checkout-button');

        checkoutButton.addEventListener('click', function() {
            fetch('/checkout', {
                    method: 'GET',
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
</body>

</html>
