@extends('auth.master')

@section('title', 'Reset password')

@section('form')

<form method="POST" action="/password/reset">
{!! csrf_field() !!}
<input type="hidden" name="token" value="{{ $token }}">

<div class="form-group">
  <label for="email">Email address</label>
  <input name="email" type="email" class="form-control" id="email" placeholder="Enter email" value="{{ old('email') }}">
</div>
<div class="form-group">
  <label for="password">Password</label>
  <input name="password" type="password" class="form-control" id="password" placeholder="Password" />
</div>
<div class="form-group">
  <label for="password_confirmation">Confirm password</label>
  <input name="password_confirmation" type="password" class="form-control" id="password_confirmation" placeholder="Password again" />
</div>
<div class="form-group">
  <button type="submit" class="btn btn-confirm btn-primary">Reset password</button>
</div>
</form>

@endsection
