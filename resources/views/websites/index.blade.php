@extends('master')

@section('title', 'Your websites')

@section('content')

<p>
<a href="{{ url('website/create') }}" class="btn btn-primary">Add a new one</a>
</p>

<table class="table">
@foreach ($websites as $website)
<tr>
        <td class="strong">
            <a href="{{ url('website/show', ['id' => $website->id]) }}">{{ $website->title }}</a>
        </td>
        <td class="text-center">
            <a href="{{ 'http://' . $website->url }}">{{ $website->url }}</a>
        </td>
        <td class="text-center">
            <a href="{{ url('website/show', ['id' => $website->id]) }}" class="btn btn-default">View</a>
        </td>
        <td class="text-center">
            <a href="{{ url('website/edit', ['id' => $website->id]) }}" class="btn btn-default">Edit</a>
        </td>
        <td class="text-right">
            <a href="{{ url('website/delete', ['id' => $website->id]) }}" class="btn btn-danger">Delete</a>
        </td>
</tr>
@endforeach
</table>

@endsection