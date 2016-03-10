@extends('master')

@section('title', 'Payment completed')

@section('content')

<p>
Thank you for your payment. Your transaction has been completed and a receipt for your purchase has been emailed to you. The payment will be processed in a minute or two.
</p>

<a href="{{ URL::route('dashboard') }}" class="btn btn-primary">Continue</a>

<style>
.alert-warning {
    display: none;
}
</style>

@endsection