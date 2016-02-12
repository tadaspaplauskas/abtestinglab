@extends('auth.master')

@section('title', 'Sign up')

@section('form')

<a href="{{ URL::route('login') }}">Already a member? Log in here.</a>

<form method="POST" action="{{ URL::route('registerPOST') }}">
{!! csrf_field() !!}
@if (isset($buy))
    <input type="hidden" name="buy" value="1">
@endif
<div class="form-group">
  <label for="name">Name</label>
  <input name="name" type="text" class="form-control" id="name" placeholder="Your name"  value="{{ old('name') }}" required>
</div>
<div class="form-group">
  <label for="email">Email address</label>
  <input name="email" type="email" class="form-control" id="email" placeholder="Enter email" value="{{ old('email') }}" required>
</div>
<div class="form-group">
  <label for="password">Password</label>
  <input name="password" type="password" class="form-control" id="password" placeholder="Password" required>
</div>
<div class="form-group">
  <label for="password_confirmation">Confirm password</label>
  <input name="password_confirmation" type="password" class="form-control" id="password_confirmation" placeholder="Password again" required>
</div>
<div class="form-group">
  <button type="submit" class="btn btn-confirm btn-primary">Sign up</button>
</div>
</form>

@endsection
