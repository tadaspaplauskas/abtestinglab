<?php
$mainLink = ['link' => URL::route('dashboard'), 'title' => 'Begin testing now'];
?>
@extends('emails.master')

@section('title', 'Welcome to A/B Testing Lab!')

@section('content')

<p>
    Happy to see You here!
    You now have access to all the features.
    Let's not waste any time - create your first test today!
</p>

@endsection