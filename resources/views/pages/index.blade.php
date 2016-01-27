<?php $noDefaultHeadline = true; ?>

@extends('master')

@section('title', 'A/B Testing Lab - split testing for content creators')

@section('content')

<!--<h1>A/B testing for content creators</h1>-->
<div class="page-header text-center">
  <h1>A/B testing for content creators. <small>Stop guessing. Start testing.</small></h1>
</div>
<p class="text-center narrow-paragraph intro-paragraph">
<strong>A/B Testing Lab</strong> lets you test and optimize your website with real visitors without them noticing anything.
<!--<br>
Not sure which headline, opening paragraph or picture to use for a new post? Test them and make an informed decision.-->
</p>
<div class="row">
    <div class="col-md-6">
        <h2>Why?</h2>
        <ul>
            <li><strong>Content is king.</strong> <!--Do not waste time obsessing over button colors - test what really matters first.--> Test what really matters.</li>
            <li>Make decisions backed up by data.</li>
            <li>Optimize your website content to serve visitors as best as possible.</li>
            <li><!--Verify or debunk your ideas and assumptions. -->See what actually works and what only looks good on paper.</li>
            <li>Create different variations of web pages using a simple visual editor.</li>
            <li>Dead-simple integration - just copy-paste a small code snippet in your website and you are ready to go.</li>
        </ul>
    </div>

    <div class="col-md-6">
    <h2>How?</h2>
    <ol>
        <!--<li>Load your website in visual test editor.</li>-->
        <li><strong>Choose the element</strong> you want to optimize.</li>
        <li><strong>Describe a variation.</strong> You may specify different styling too.</li>
        <li><strong>Choose a conversion event.</strong> How will you measure success?</li>
        <li><strong>Choose the reach of your test.</strong> How many users should participate?</li>
        <li><strong>Let the test run for a while.</strong> We will notify you when it ends.</li>
        <li><strong>Review the results</strong> and make changes accordingly.</li>
    </ol>
    </div>
</div>

<p class="text-center conversion">
    <a href="{{ URL::route('register') }}" class="btn btn-lg btn-primary">Start my free trial now</a>
</p>

<h2>Pricing</h2>
<p>
    Do not worry, when you sign up your <strong>first 3,000 visitors are on us</strong>. After that there are no hidden or monthly fees - pay only for what you use.
</p>

@include('pages.pricing_table')

<p class="text-center more-questions">
Have more questions? Check out our <a href="{{ URL::route('faq') }}">F.A.Q.</a>
or <a href="{{ URL::route('contact') }}">contact us</a>.
</p>
@endsection