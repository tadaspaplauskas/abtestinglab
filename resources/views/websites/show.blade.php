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
    <a href="{{ route('websites.stop', ['id' => $website->id]) }}" class="btn btn-default">Pause all testing</a>

    <a href="{{ route('websites.edit', ['id' => $website->id]) }}" class="btn btn-default">
        {{--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>--}}
        Edit website</a>
    <a href="{{ route('websites.delete', ['id' => $website->id]) }}" class="btn btn-danger">
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
        <a href="{{ route('websites.archived', ['id' => $website->id]) }}" class="btn btn-default">See archived tests</a>
    </p>
@endif

@endsection