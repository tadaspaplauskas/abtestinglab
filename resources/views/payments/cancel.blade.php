@extends('master')

@section('title', 'Payment completed')

@section('content')

<p>
Your payment was cancelled.
</p>

<a href="{{ URL::route('pricing') }}" class="btn btn-primary">Continue</a>

@endsection