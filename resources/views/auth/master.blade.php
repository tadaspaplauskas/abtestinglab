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
       Did you know...
    </div>
    <div class="panel-body">
    ...that earth is round?
    </div>
    </div>
</div>    
</div>
    
@endsection
