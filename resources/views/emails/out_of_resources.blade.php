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
    You can no longer run any tests because you ran out of resources. Please consider purchasing more to resume testing.
</p>

@endsection