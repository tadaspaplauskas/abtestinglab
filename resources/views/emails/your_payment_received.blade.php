<?php
$mainLink = ['link' => URL::route('dashboard'), 'title' => 'Go to A/B Testing Lab'];
?>
@extends('emails.master')

@section('title', 'We received the payment')

@section('content')

<p>
    Hey, {{ $user->name }}
</p>
<p>
    Just wanted to let you know that we received your payment of {{ $payment->gross }} and you can start using your new resources right away.
</p>

@endsection