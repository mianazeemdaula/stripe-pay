@extends('layouts.admin')
@section('body')
    <div class="container px-6">
        <!--welcome  -->
        <div class="flex items-center py-6">
            <div class="flex-1">
                <div class="bread-crumb">
                    <a href="{{ url('user') }}" class="link">Home</a>
                    <div>/</div>
                    <div>Withdrawal</div>
                </div>
            </div>
            <div class="text-slate-500">{{ today()->format('d/m/Y') }}</div>
        </div>

        <!-- middle content panel starts-->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-y-4 md:gap-x-4 mt-8">
            <div class="relative col-span-2">
                <!-- user has already taken some course -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <p><strong>Opps Something went wrong</strong></p>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <!-- if student successfully cretated -->
                @if (session('success'))
                    <div class="alert-success mb-8">
                        <i class="bi-emoji-smile text-[24px] mr-4"></i>
                        {{ session('success') }}
                    </div>
                @endif
                <div class="p-6 rounded-lg  bg-white">
                    <div class="h2">Withdrawal</div>
                    <div class="text-slate-500 mt-1">Please provide following information</div>
                    <form action="{{ route('manager.withdrawals.store') }}" method='post' class="flex flex-col w-full mt-4"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-1 lg:grid-cols-2 mt-3 text-slate-600 gap-4">
                            <div class="flex flex-col">
                                <label for="">Amount*</label>
                                <input type="text" id="amount" name="amount" value="{{ old('amount') }}"
                                    class="border border-gray-300 p-2 rounded-lg" placeholder="Enter Amount">
                                @error('amount')
                                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="bg-green-500 p-2 rounded text-white">Submit Now</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div>
                <div class="p-6 bg-white">
                    <div>
                        Current Balance
                    </div>
                    <div class="font-bold" id="balance"></div>

                    <div>
                        Fee
                    </div>
                    <div class="font-bold" id="feevalue"></div>

                    <div>
                        Amount to be paid
                    </div>
                    <div class="font-bold" id="amounttobepaid"></div>

                </div>
            </div>

        </div>
    </div>
@endsection
@section('script')
    <script>
        function calculate() {
            let amount = this.options[this.selectedIndex].getAttribute('data-amount');
            let fee = this.options[this.selectedIndex].getAttribute('data-fee');
            document.getElementById('amount').value = amount;
            document.getElementById('fee').value = fee;
            document.getElementById('balance').innerHTML = amount;
            document.getElementById('feevalue').innerHTML = fee * amount / 100;
            document.getElementById('amounttobepaid').innerHTML = amount - (fee * amount / 100);
        }
        // on change of the user id select the data-amount and set the value to the amount field
        document.getElementById('user_id').addEventListener('change', function() {
            calculate.call(this);
        });
        // on change of the amount field calculate
        document.getElementById('amount').addEventListener('change', function() {
            // check if the amount enter more then the balance
            let balance = document.getElementById('user_id').options[document.getElementById('user_id')
                    .selectedIndex]
                .getAttribute('data-amount');
            if (this.value > balance) {
                alert('Amount can not be more then the balance');
                this.value = balance;
            }
            let amount = this.value;
            let fee = document.getElementById('fee').value;
            document.getElementById('balance').innerHTML = amount;
            document.getElementById('feevalue').innerHTML = fee * amount / 100;
            document.getElementById('amounttobepaid').innerHTML = amount - (fee * amount / 100);
        });

        // on change of the fee field
        document.getElementById('fee').addEventListener('change', function() {
            let fee = this.value;
            let amount = document.getElementById('amount').value;
            document.getElementById('balance').innerHTML = amount;
            document.getElementById('feevalue').innerHTML = fee * amount / 100;
            document.getElementById('amounttobepaid').innerHTML = amount - (fee * amount / 100);
        });
        // on load calculate the amount
        calculate.call(document.getElementById('user_id'));
    </script>
@endsection
