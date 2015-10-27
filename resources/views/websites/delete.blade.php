@extends('master')

@section('title', 'Delete ' . $website->title)

@section('breadcrumbs', Breadcrumbs::render('website', $website))

@section('content')

<form role="form" method="POST" action="{{ url('website/destroy') }}">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="website_id" value="{{ $website->id }}">

    <p>Are you sure? Deleted websites cannot be recovered!</p>
    
    <button type="submit" class="btn btn-danger">Delete {{ $website->title }}</button>
</form>

@endsection