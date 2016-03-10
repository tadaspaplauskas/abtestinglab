



<?php
$mainLink = ['link' => URL::route('websites.show', [$test->website_id]), 'title' => 'Go to A/B Testing Lab'];
?>
@extends('emails.master')

@section('title', 'The test is completed')

@section('content')

<p>
    Hey, {{ $user->name }}
</p>
<p>
    Your test "{{ $test->title }}" is completed,
    head over to A/B Testing Lab to view the results and make changes accordingly.
</p>

@endsection