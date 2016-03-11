<?php
$mainLink = ['link' => URL::route('dashboard'), 'title' => 'Go to A/B Testing Lab'];
?>
@extends('emails.master')

@section('title', 'Payment received')

@section('content')

<p>
    Hi, {{ $user->name }}
</p>
<p>
    Just wanted to let you know that your payment of {{ $payment->gross }} has been received and you can start running tests right away.
</p>

@endsection