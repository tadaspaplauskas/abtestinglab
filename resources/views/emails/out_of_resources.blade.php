<?php
$mainLink = ['link' => URL::route('pricing'), 'title' => 'Buy now'];
?>
@extends('emails.master')

@section('title', 'You are out of resources')

@section('content')

<p>
    Hi, {{ $user->name }}
</p>
<p>
    Too bad you used all your tests. All your activity has been stopped. To continue testing consider buying more resources.
</p>

@endsection