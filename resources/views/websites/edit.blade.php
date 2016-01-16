@extends('master')

@section('title', 'Edit ' . $website->title)
@section('breadcrumbs', Breadcrumbs::render('website', $website))

@section('content')

<form role="form" method="POST" action="{{ route('websites.store') }}">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="website_id" value="{{ $website->id or '' }}">

    <div class="form-group">
        <label for="url">Website url</label>
        <input name="url" id="url" type="text" class="form-control" value="{{ old('url', $website->url) }}" placeholder="https://">
    </div>
    <div class="form-group">
        <label for="title">Website title</label>
        <input name="title" id="title" type="text" class="form-control" value="{{ old('title', $website->title) }}">
    </div>

    <div class="form-group">
        <label for="title">Keep the winning variations</label>
        <label class="explain">
        <input {{ (old('keep_best_variation', $website->keep_best_variation) == 1) ? 'checked' : '' }} name="keep_best_variation" id="keep_best_variation" type="checkbox" value="1">
        Display the best performing variation after the test has completed.
        </label>
    </div>

    <button type="submit" class="btn btn-primary">Save</button>
</form>



@endsection