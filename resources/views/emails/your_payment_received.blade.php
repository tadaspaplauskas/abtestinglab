<?php
$mainLink = ['link' => URL::route('dashboard'), 'title' => 'Go to A/B Testing Lab'];
?>
@extends('emails.master')

@section('title', 'We received the payment')

@section('content')

<p>
    Hi, {{ $user->name }}
</p>
<p>
    Just wanted to let you know that we received your payment of {{ $payment->gross }} and you can start creating new tests right away.
</p>

@endsection