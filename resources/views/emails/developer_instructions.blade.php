@extends('emails.master')

@section('title', 'Instructions to the developer')

@section('content')

<p>
    Hi! {{ $user->name }} is about to try out <a href="{{ url('/') }}">A/B Testing Lab</a>, but before that you must install our script on your website <a href="{{ $website->url }}">{{ $website->title }}</a>.
    Add the JS code below to the &lt;head&gt; tag of your website.
    This code should be loaded in every page where you want to conduct tests.
</p>
<p>
    {!! $website->jsCodeTextarea() !!}
</p>

@endsection