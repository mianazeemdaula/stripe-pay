@extends('layouts.web')
@section('body')
    <div class="bg-green-400 flex items-center justify-center min-h-screen">
        <form id="amountForm" class="p-4 items-center justify-between flex flex-col">
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
            <div>
                <div id="cash-app-button"></div>
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
    <script src="https://sandbox.web.squarecdn.com/v1/square.js"></script>

    <script type="text/javascript">
        const applicationId = "{{ env('SQUARE_APPLICATION_ID') }}";
        const locationId = "{{ env('SQUARE_LOCATION_ID') }}";

        function buildPaymentRequest(payments, amount) {
            const paymentReqeust = payments.paymentReqeust({
                requestShippingAddress: false,
                requestBillingInfo: true,
                currencyCode: 'USD',
                countryCode: 'US',
                total: {
                    amount: amount,
                    label: 'Total'
                },
                lineItems: [{
                    amount: amount,
                    label: 'Total',
                    pending: false
                }]
            });
            return paymentReqeust;
        }

        async function initializeCashApp(payments, amount) {
            const paymentReqeust = buildPaymentRequest(payments, amount);
            const cashAppPay = await payments.cashAppPay(paymentReqeust, {
                redirectURL: 'https://example.com/success',
                referenceId: '1234',
            });

            const buttonOptinos = {
                shape: 'semiround',
                width: 'full',
            }
            await cashAppPay.attach('#cash-app-button', buttonOptinos);
            return cashAppPay;
        }

        async function createPayment(token) {
                const body = Json.stringify({
                    locationId: locationId,
                    sourceId: token,
                });

                const paymentResponse = await fetch('https://connect.squareupsandbox.com/v2/payments', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',

                            }
                            document.getElementById('amountForm').addEventListener('submit', async function(e) {
                                e.preventDefault();
                                let amount = document.getElementById('amount').value;
                                const payments = window.Square.payments(applicationId, locationId);
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
