@extends('layouts.admin')
@section('body')
    @if (auth()->user()->type == 'admin')
        Balance $: {{ $balance->available[0]['amount'] / 100 }}
        Pending $: {{ $balance->pending[0]['amount'] / 100 }}
    @endif

    <div class="w-96">

        <canvas id="myChart">

        </canvas>
    </div>
@endsection


@section('script')
    <script type="module">
        const ctx = document.getElementById('myChart');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($sales->pluck('date')),
                datasets: [{
                    label: "Sales of last {{ $sales->count() }} days",
                    data: @json($sales->pluck('total')),
                    borderWidth: 2
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection
