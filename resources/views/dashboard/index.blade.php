@extends('master')

@section('title', 'Dashboard')

@section('content')



<div class="row">
<div class="col-md-6">
    <div class="panel panel-default">
    <div class="panel-heading">
       Recently completed tests
    </div>
    <div class="panel-body">
        @if ($stopped->isEmpty())
            <p>Nothing new here.</p>
        @else
            <ul>
                @foreach($stopped as $test)
                <li>
                    <a href="{{ url('website/show', ['id' => $test->website_id]) }}#test-{{ $test->id }}">
                        {{ $test->title }}
                    </a> in 
                    <a href="{{ url('website/show', ['id' => $test->website_id]) }}">
                        {{ $test->website->title }}
                    </a> 
                </li>
                @endforeach
            </ul>
        @endif
    </div>
    </div>
</div>
<div class="col-md-6">
    <div class="panel panel-default">
    <div class="panel-heading">
       Most recent running tests
    </div>
    <div class="panel-body">
        @if ($lastUpdated->isEmpty())
            <p>
                Not much is going on here. Wanna get to the testing?<br>
                <a href="{{ url('website/create') }}">Add a new website</a> or 
                <a href="{{ url('website/index') }}">create new tests</a>.
            </p>
        @else
            <ul>
                @foreach($lastUpdated as $test)
                <li>
                    <a href="{{ url('website/show', ['id' => $test->website_id]) }}#test-{{ $test->id }}">
                        {{ $test->title }}
                    </a> in 
                    <a href="{{ url('website/show', ['id' => $test->website_id]) }}">
                        {{ $test->website->title }}
                    </a> 
                </li>
                @endforeach
            </ul>
        @endif
    </div>
    </div>
</div>
</div>



@endsection