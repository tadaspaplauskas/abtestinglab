@extends('master')

@section('title', 'User settings')

@section('content')

<div class="row">
<div class="col-md-6">
    <div class="panel panel-default">
    <div class="panel-heading">
       User profile
    </div>
    <div class="panel-body">

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

        <div class="form-group required">
            <label for="url">Name</label>
            <input name="url" id="url" type="text" class="form-control" value="{{ $website->url or '' }}">
        </div>
        <div class="form-group required">
            <label for="title">Email</label>
            <input name="title" id="title" type="text" class="form-control" value="{{ $website->title or '' }}">
        </div>
        <div class="form-group required">
            <label for="title">Old password</label>
            <input name="title" id="title" type="text" class="form-control" value="{{ $website->title or '' }}">
        </div>
        <hr>
        <div class="form-group">
            <label for="title">New password</label>
            <input name="title" id="title" type="text" class="form-control" value="{{ $website->title or '' }}">
        </div>
        <div class="form-group">
            <label for="title">Repeat new password</label>
            <input name="title" id="title" type="text" class="form-control" value="{{ $website->title or '' }}">
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    </div>
    </div>
</div>
<div class="col-md-6">
    <div class="panel panel-default">
    <div class="panel-heading">
       Email notifications
    </div>
    <div class="panel-body">
        Send me weekly reports
        Send me a notification when test is completed
        Subscribe me to AB Testing Lab newsletter (no more than one email per week)
    </div>
    </div>
</div>
</div>

@endsection