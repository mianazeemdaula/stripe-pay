@extends('layouts.admin')
@section('body')
    @if (auth()->user()->type == 'admin')
        Balance: {{ $balance->available[0]['amount'] }}
        Pending: {{ $balance->pending[0]['amount'] }}
    @endif
@endsection
