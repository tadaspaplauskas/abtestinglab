@extends('auth.master')

@section('title', 'Log in')

@section('form')

<form method="POST" action="{{ URL::route('loginPOST') }}">
{!! csrf_field() !!}
<div class="form-group">
  <label for="email">Email address</label>
  <input name="email" type="email" class="form-control" id="email" placeholder="Enter email" value="{{ old('email') }}" required>
</div>
<div class="form-group">
  <label for="password">Password</label>
  <input name="password" type="password" class="form-control" id="password" placeholder="Password" required>
</div>
<div class="form-group">
    <div class="checkbox">
        <label><input type="checkbox" name="remember"> Remember Me</label>
    </div>
</div>
<div class="form-group">
  <button type="submit" class="btn btn-confirm btn-primary">Log in</button>
</div>
</form>
<p>
<small>
<a href="{{ URL::route('password.reset') }}">Forgot your password?</a>
</small>
</p>

@endsection
