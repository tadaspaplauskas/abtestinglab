@extends('master')

@section('title', 'Delete ' . $test->title)

@section('breadcrumbs', Breadcrumbs::render('test', $test))

@section('content')

<form role="form" method="POST" action="{{ route('tests.destroy') }}">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="test_id" value="{{ $test->id }}">

    <p>Are you sure? Deleted tests cannot be recovered!</p>
    
    <button type="submit" class="btn btn-danger">Delete {{ $test->title }}</button>
</form>

@endsection