@extends('master')

@section('title', 'Archive: ' . $website->title)

@section('breadcrumbs', Breadcrumbs::render('archived_tests', $website))

@section('content')

@if ($website->archivedTests->isEmpty())
    <p>Nothing to see here.</p>
@else

    <?php $tests = $website->archivedTests; ?>
    @include('tests.tests_table')

@endif
<hr>
<p>
    <a href="{{ url('website/show', ['id' => $website->id]) }}" class="btn btn-default">See active tests</a>
</p>

@endsection