@extends('master')

@section('title', 'User settings')

@section('content')

<form role="form" method="POST" action="{{ route('user.update') }}">
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

    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <div class="form-group required">
        <label for="name">Name</label>
        <input name="name" id="name" type="text" class="form-control" value="{{ $user->name }}">
    </div>
    <div class="form-group required">
        <label for="email">Email {{ Input::old('email') }}</label>
        <input name="email" id="email" type="text" class="form-control" value="{{ $user->email }}">
    </div>
    <div class="form-group required">
        <label for="old_password">Old password</label>
        <small>required to change email or password</small>
        <input name="old_password" id="old_password" type="password" class="form-control">
    </div>
    <hr>
    <div class="form-group">
        <label for="new_password">New password</label>
        <input name="new_password" id="new_password" type="password" class="form-control">
    </div>
    <div class="form-group">
        <label for="new_password_verification">Repeat new password</label>
        <input name="new_password_verification" id="new_password_verification" type="password" class="form-control">
    </div>
    </div>
    </div>
</div>
<div class="col-md-6">
    <div class="panel panel-default">
    <div class="panel-heading">
       Email notifications
    </div>
    <div class="panel-body">
        <div class="form-group">
            <input {{ $user->weekly_reports ? 'checked' : ''  }} name="weekly_reports" id="weekly_reports" type="checkbox" value="1">
            <label for="weekly_reports"> Send me weekly reports</label>
        </div>
        <div class="form-group">
            <input {{ $user->test_notifications ? 'checked' : ''  }} name="test_notifications" id="test_notifications" type="checkbox" value="1">
            <label for="test_notifications"> Send me a notification when test is completed</label>
        </div>
        <div class="form-group">
            <input {{ $user->newsletter ? 'checked' : ''  }} name="newsletter" id="newsletter" type="checkbox" value="1">
            <label for="newsletter"> Subscribe to the newsletter (no more than once a week)</label>
        </div>
    </div>
    </div>
    <div class="panel panel-default">
    <div class="panel-heading">
       Payments
    </div>
    <div class="panel-body">
        <p>
            You have <strong>{{ $user->getAvailable() }}</strong> unique test views left to use. Do not wait until
            you run out - <a href="{{ route('pricing') }} ">buy more now</a>.
        </p>
        <p>
            <a href="{{ route('payments') }}">Your previous payments</a>.
        </p>
    </div>
    </div>
</div>
</div>

<button type="submit" class="btn btn-primary">Submit</button>
</form>

@endsection