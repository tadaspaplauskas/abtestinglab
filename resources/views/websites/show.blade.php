@extends('master')

@section('title', $website->title . ' - test list')

@section('breadcrumbs', Breadcrumbs::render('website', $website))

@section('content')

<div class="row actions-menu">
<div class="col-md-7 text-left">
    <a href="{{ route('tests.manager', ['id' => $website->id]) }}" class="btn btn-primary">Manage tests</a>
    @if ($website->unpublishedChanges() || true)
        <a href="{{ route('tests.publish', ['id' => $website->id]) }}" class="btn btn-default">Publish test changes</a>
    @else
        <a class="btn btn-default disabled">Publish changes</a>
    @endif
    
    @if ($website->published_at > 0)
        <small>Last published: {{ $website->published_at }}</small>
    @endif
</div>
<div class="col-md-5 text-right">
    @if($website->isEnabled())
        <a href="{{ route('website.disable', ['id' => $website->id]) }}" class="btn btn-default">Disable website</a>
    @else
        <a href="{{ route('website.enable', ['id' => $website->id]) }}" class="btn btn-default">Enable website</a>
    @endif

    <a href="{{ route('website.edit', ['id' => $website->id]) }}" class="btn btn-default">Edit website</a>
    <a href="{{ route('website.delete', ['id' => $website->id]) }}" class="btn btn-danger">Delete website</a>
</div>
</div>


@if ($website->tests->isEmpty())
    <p>Create your first test now!</p>
@else
    <?php $tests = $website->tests; ?>
    @include('websites.tests_table')
@endif
<hr>
<p>
    <a href="{{ url('website/show/archived', ['id' => $website->id]) }}" class="btn btn-default">See archived tests</a>
</p>
<hr>
<div>
<h2>JS code</h2>
<p>
    Add this JS code in the &lt;head&gt; tag of your website.
    This code should be loaded in every page where you want to conduct tests.
</p>
<p>
    <input type="text" style="width: 100%" readonly onclick="this.focus();this.select()" value='<script type="text/javascript" src="{{ $website->jsUrl() }}" async></script>'>
</p>
</div>

@endsection