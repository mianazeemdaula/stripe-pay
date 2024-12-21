<!doctype html>
<html>

<head>
    <title>Cash App Payment</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="{{ asset('/css/square.css') }}" rel="stylesheet" />
    @if (env('SQUARE_ENVIRONMENT') == 'sandbox')
        <script type="text/javascript" src="https://sandbox.web.squarecdn.com/v1/square.js"></script>
    @else
        <script type="text/javascript" src="https://web.squarecdn.com/v1/square.js"></script>
    @endif
    <script>
        const appId = "{{ env('SQUARE_APPLICATION_ID') }}";
        const locationId = "{{ env('SQUARE_LOCATION_ID') }}";

        function buildPaymentRequest(payments) {
            const paymentRequest = payments.paymentRequest({
                countryCode: 'US',
                currencyCode: 'USD',
                total: {
                    amount: '{{ $amount }}',
                    label: 'Total',
                },
            });
            return paymentRequest;
        }

        async function initializeCashApp(payments) {
            const paymentRequest = buildPaymentRequest(payments);
            const cashAppPay = await payments.cashAppPay(paymentRequest, {
                redirectURL: "{{ url('invoice-success', $tag) }}",
                referenceId: "{{ $tag }}",
            });
            const buttonOptions = {
                shape: 'semiround',
                width: 'full',
            };
            await cashAppPay.attach('#cash-app-pay', buttonOptions);
            return cashAppPay;
        }

        async function createPayment(token) {
            const body = JSON.stringify({
                locationId,
                sourceId: token,
                idempotencyKey: window.crypto.randomUUID(),
                amount: '{{ $amount }}',
                referenceId: "{{ $tag }}",
            });

            const paymentResponse = await fetch('/sqaure/cashapp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body,
            });

            if (paymentResponse.status !== 200) {
                const errorBody = await paymentResponse.text();
                throw new Error(errorBody);
            }

            if (paymentResponse.ok) {
                return paymentResponse.json();
            }
            const errorBody = await paymentResponse.text();
            throw new Error(errorBody);
        }

        // status is either SUCCESS or FAILURE;
        function displayPaymentResults(status) {
            const statusContainer = document.getElementById(
                'payment-status-container',
            );
            if (status === 'SUCCESS') {
                statusContainer.classList.remove('is-failure');
                statusContainer.classList.add('is-success');
            } else {
                statusContainer.classList.remove('is-success');
                statusContainer.classList.add('is-failure');
            }
            statusContainer.style.visibility = 'visible';
        }

        document.addEventListener('DOMContentLoaded', async function() {
            const loader = document.getElementById('loading-spinner');
            loader.style.display = 'block';
            if (!window.Square) {
                throw new Error('Square.js failed to load properly');
            }

            let payments;
            try {
                payments = window.Square.payments(appId, locationId);
            } catch (e) {
                console.error('Initializing Square Payments failed', e);
                const statusContainer = document.getElementById(
                    'payment-status-container',
                );
                statusContainer.className = 'missing-credentials';
                statusContainer.style.visibility = 'visible';
                return;
            } finally {
                loader.style.display = 'none';
            }

            let cashAppPay;
            try {
                cashAppPay = await initializeCashApp(payments);
            } catch (e) {
                console.error('Initializing Cash App Pay failed', e);
            }
            if (cashAppPay) {
                cashAppPay.addEventListener(
                    'ontokenization',
                    async function({
                        detail
                    }) {
                        const tokenResult = detail.tokenResult;
                        console.log("tokenResult", detail);
                        if (tokenResult.status === 'OK') {
                            const paymentResults = await createPayment(tokenResult.token);
                            displayPaymentResults('SUCCESS');
                            console.debug('Payment Success', paymentResults);
                        } else {
                            let errorMessage = `Tokenization failed with status: ${tokenResult.status}`;

                            if (tokenResult.errors) {
                                errorMessage += ` and errors: ${JSON.stringify(
                    tokenResult.errors,
                  )}`;
                            }
                            displayPaymentResults('FAILURE');
                            throw new Error(errorMessage);
                        }
                    },
                );
            }
        });
    </script>
</head>

<body>
    <form id="payment-form">
        <img src="{{ asset('images/cashapp.png') }}" alt="cash app" srcset="" style="width:100px">
        <h1>Pay with Cash App</h1>
        <div id="loading-spinner" style="display:none;">Loading...</div>
        <div class=""> You are paying ${{ $amount }}</div>
        <div id="cash-app-pay"></div>
        <div id="card-container"></div>
    </form>
    <div id="payment-status-container"></div>
</body>

</html>
