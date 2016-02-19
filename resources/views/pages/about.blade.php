@extends('master')

@section('title', 'About us')

@section('content')

<div class="row">
<div class="col-md-6">
My name is Tadas Paplauskas and I'm a web developer from Lithuania. I'm the guy behind A/B Testing Lab. This is my passion project and I want to make it the awesome tool for content creators to test their ideas and draw insights about their visitors.
</div>

<div class="col-md-6">
<strong>A/B Testing Lab</strong> was born out of the need to run a few simple A/B tests at my job some years ago. To my surprise I saw many great solutions for marketers, but there was no easy and fast way to test ideas about your visitors for the uninitiated. After a bit of research I rolled up my sleeves and got to work.
</div>
</div>

<p class="text-center" style="margin-top: 2em">
<strong>Wanna help make A/B Testing Lab better?</strong> <a href=" {{ URL::route('contact') }}">Contact me</a> if you have any comments or ideas for improvement. I'm always excited to hear about your thoughts and experiences.
</p>

@endsection