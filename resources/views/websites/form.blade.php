@extends('master')

@if (isset($website))
    @section('title', 'Edit ' . $website->title)
    @section('breadcrumbs', Breadcrumbs::render('website', $website))
@else
    @section('title', 'Add a new website')
    @section('breadcrumbs', Breadcrumbs::render('website'))
@endif



@section('content')

@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form role="form" method="POST" action="{{ route('website.store') }}">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="website_id" value="{{ $website->id or '' }}">
    
    <div class="form-group">
        <label for="url">Website url</label>
        <input name="url" id="url" type="text" class="form-control" value="{{ $website->url or '' }}" placeholder="https://">
    </div>
    <div class="form-group">
        <label for="title">Website title</label>
        <input name="title" id="title" type="text" class="form-control" value="{{ $website->title or '' }}">
    </div>
    
    <div class="form-group">
        <label for="title">Keep the winning variations</label>
        <label class="explain">
        <input {{ ($website->keep_best_variation == 1) ? 'checked' : '' }} name="keep_best_variation" id="keep_best_variation" type="checkbox" value="1">
        After the test is completed display the best variation on the webpage.
        </label>
    </div>
    
    <button type="submit" class="btn btn-primary">Save</button>
</form>



@endsection