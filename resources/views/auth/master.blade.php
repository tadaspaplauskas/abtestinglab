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
    </div>
    </div>
</div>
</div>

@endsection
