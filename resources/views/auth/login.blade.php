@extends('auth.master')

@section('title', 'Join in')

@section('form')

<form method="POST" action="/auth/login">
{!! csrf_field() !!}
<div class="form-group">
  <label for="email">Email address</label>
  <input name="email" type="email" class="form-control" id="email" placeholder="Enter email" value="{{ old('email') }}" />
</div>
<div class="form-group">
  <label for="password">Password</label>
  <input name="password" type="password" class="form-control" id="password" placeholder="Password" />
</div>
<div class="form-group">
    <div class="checkbox">
        <label><input type="checkbox" name="remember"> Remember Me</label>
    </div>
</div>
<div class="form-group">
  <button type="submit" class="btn btn-confirm">Sign in</button>
</div>
</form>

@endsection
