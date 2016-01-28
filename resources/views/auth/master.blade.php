@extends('master')


@section('content')

<div class="row">
<div class="col-md-6">
    <div class="panel panel-default">
    <div class="panel-heading">
       @yield('title')
    </div>
    <div class="panel-body">

    @yield('form')

    </div>
    </div>
</div>



<div class="col-md-6">
    <div class="panel panel-default">
    <div class="panel-heading">
       Social logins
    </div>
    <div class="panel-body">
        Facebook, google

        <p>
        By signing up you are agreeing to <a href="{{ URL::route('privacy') }}">Privacy Policy</a>.
        </p>
    </div>
    </div>
</div>
</div>

@endsection
