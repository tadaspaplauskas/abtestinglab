<?php
$mainLink = ['link' => URL::route('pricing'), 'title' => 'Buy now'];
?>
@extends('emails.master')

@section('title', 'Instructions to the developer')

@section('content')

<p>
    Hi, {{ $user->name }}
</p>
<p>
    Too bad you used all your tests. Your running tests have been stopped. To continue testing consider buying more resources.
</p>

@endsection