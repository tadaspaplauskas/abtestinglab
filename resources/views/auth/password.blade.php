@extends('auth.master')

@section('title', 'Reset password')

@section('form')

<form method="POST" action="/password/email">
{!! csrf_field() !!}
<div class="form-group">
  <label for="email">Email address</label>
  <input name="email" type="email" class="form-control" id="email" placeholder="Enter email" value="{{ old('email') }}">
</div>
<div class="form-group">
  <button type="submit" class="btn btn-confirm btn-primary">Send password reset link</button>
</div>
</form>

@endsection