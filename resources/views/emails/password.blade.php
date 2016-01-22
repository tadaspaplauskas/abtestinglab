<?php
$mainLink = ['link' => url('password/reset/'.$token), 'title' => 'Reset password'];
?>
@extends('emails.master')

@section('title', 'Welcome to A/B Testing Lab!')

@section('content')

<p>
    Visit the link to reset your password.
</p>

@endsection