@extends('master')

@section('title', $website->title . ' - test list')

@section('breadcrumbs', Breadcrumbs::render('website', $website))

@section('content')

<div class="row actions-menu">
<div class="col-md-6 text-left">
    <a href="{{ route('tests.manager', ['id' => $website->id]) }}" class="btn btn-primary">
        Manage tests</a>
    <small>Last changes: {{ $website->lastChangesForHumans() }}</small>
</div>
<div class="col-md-6 text-right">
    <a href="{{ route('website.stop', ['id' => $website->id]) }}" class="btn btn-default">Pause all testing</a>

    <a href="{{ route('website.edit', ['id' => $website->id]) }}" class="btn btn-default">
        {{--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>--}}
        Edit website</a>
    <a href="{{ route('website.delete', ['id' => $website->id]) }}" class="btn btn-danger">
        {{--<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>--}}
        Delete website</a>
</div>
</div>


@if ($website->tests->isEmpty())
    <p class="text-center"><a href="{{ route('tests.manager', ['id' => $website->id]) }}">Create your first test now!</a></p>
@else
    <?php $tests = $website->tests; ?>
    @include('tests.tests_table')
    <hr>
    <p>
        <a href="{{ route('website.archived', ['id' => $website->id]) }}" class="btn btn-default">See archived tests</a>
    </p>
@endif
<hr>
<div>
<h2>JS code</h2>
<p>
    Add this JS code in the &lt;head&gt; tag of your website.
    This code should be loaded in every page where you want to conduct tests.
</p>
<p>
    <input type="text" style="width: 100%" readonly onclick="this.focus();this.select()" value='<script type="text/javascript" src="{{ $website->jsUrl() }}" async></script><script type="text/javascript">var s=document.createElement("style");s.type="text/css";s.appendChild(document.createTextNode("body{visibility:hidden;}"));document.getElementsByTagName("head")[0].appendChild(s);setTimeout(function(){document.body.style.visibility = "initial";},500);</script>'>
</p>
</div>

@endsection