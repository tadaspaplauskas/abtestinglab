@extends('master')

@section('title', '404 Not Found')

@section('content')

<p>
    @if($exception->getMessage())
        {{ $exception->getMessage() }}
    @else
        Uh-oh, seems like this link does not lead to anything...
    @endif
</p>

@endsection