@extends('master')

@if (isset($website))
    @section('title', 'Edit ' . $website->title)
@else
    @section('title', 'Add a new website')
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

<form role="form" method="POST" action="{{ url('website/store') }}">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="website_id" value="{{ $website->id or '' }}">
    
    <div class="form-group">
        <label for="url">Website url</label>
        <input name="url" id="url" type="text" class="form-control" value="{{ $website->url or '' }}">
    </div>
    <div class="form-group">
        <label for="title">Website title</label>
        <input name="title" id="title" type="text" class="form-control" value="{{ $website->title or '' }}">
    </div>
    
    <button type="submit" class="btn btn-primary">Submit</button>
</form>



@endsection