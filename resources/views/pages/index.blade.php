<?php $noDefaultHeadline = true; ?>

@extends('master')

@section('title', 'A/B Testing Lab - split testing for content creators')

@section('content')
<div class="page-header text-center">
  <h1>A/B testing for content creators. <small>Stop guessing. Start testing.</small></h1>
</div>
<p class="text-center narrow-paragraph intro-paragraph">
<strong>A/B Testing Lab</strong> makes it easy to deliver the best experience for every visitor.
</p>

<div class="text-center">
    <h2>Why?</h2>
    <ul class="list-unstyled">
        <li><strong>Content is king.</strong> Test what really matters.</li>
        <li>Increase user engagement.</li>
        <li>Optimize your website to serve visitors as good as possible.</li>
        {{--<li>Make decisions backed up by data.</li>--}}
        {{--<li>See what actually works and what only looks good on paper.</li>--}}
        <li>Create different variations of your content using a simple visual editor.</li>
        <li>Dead-simple integration - just copy-paste a small code snippet into your website and you are ready to go.</li>
    </ul>
</div>

<div class="row" style="margin-top: 3em">
    <div class="col-md-6">
        <h2>How?</h2>
        <ol>
            <li><strong>Choose the element</strong> you want to optimize.</li>
            <li><strong>Describe a variation.</strong> You may specify different styling too.</li>
            <li><strong>Choose a conversion event.</strong> How will you measure success?</li>
            <li><strong>Choose the reach of your test.</strong> How many users should participate?</li>
            <li><strong>Let the test run for a while.</strong> We will notify you when it ends.</li>
            <li><strong>Review the results</strong> and make changes accordingly.</li>
        </ol>
    </div>
    <div class="col-md-6">
        <img src="{{ url('assets/img/demo.png') }}" class="img-responsive img-thumbnail">
    </div>
</div>

<div class="text-center conversion">
    <a href="{{ URL::route('register') }}" class="btn btn-lg btn-primary">Start my free trial now</a>
</div>
<div class="text-center">
    <h3>or</h3>
</div>
<div class="text-center">
    <form class="form-inline" action="{{ URL::route('email_subscriptions.store') }}" method="POST" style="margin-top: 2em">
        Consider subscribing to the newsletter for news, tips & tricks and more <small>(no more than once a week)</small><br>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input class="form-control" type="email" name="email" placeholder="Your email address">
        <input class="form-control" type="text" name="name" placeholder="Your name">
        <button type="submit" class="btn btn-default">Subscribe</button>
    </form>
</div>

<h2>Pricing</h2>
<p>
    Do not worry, when you sign up your <strong>first 3,000 visitors tested are on us</strong>. After that there are no hidden or monthly fees - pay only for what you use.
</p>

@include('pages.pricing_table')

<p class="text-center more-questions">
Have more questions? Check out our <a href="{{ URL::route('faq') }}">F.A.Q.</a>
or <a href="{{ URL::route('contact') }}">contact us</a>.
</p>
@endsection