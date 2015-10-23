@extends('auth.master')

@section('title', 'Join in')

@section('form')

<form method="POST" action="/auth/register">
{!! csrf_field() !!}
<div class="form-group">
  <label for="name">Name</label>
  <input name="name" type="text" class="form-control" id="name" placeholder="Your name"  value="{{ old('name') }}">
</div>
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
  <button type="submit" class="btn btn-confirm">Register</button>
</div>
</form>
    
@endsection
