@extends('master')

@section('title', 'Your websites')

@section('breadcrumbs', Breadcrumbs::render('websites'))

@section('content')

<p>
<a href="{{ url('website/create') }}" class="btn btn-primary">Add a new one</a>
</p>

@if($websites->isEmpty())
<p class="text-center">You haven't added any websites yet. Let's <a href="{{ url('website/create') }}">do that</a> now.</p>
@else
    <table class="table">
        <tr>
            <th class="text-center">Title</th>
            <th class="text-center">Address</th>
            <th class="text-right">Running tests</th>
            <th class="text-right">Total tests</th>
            <th class="text-right">Last published</th>
            <th class="text-right">Actions</th>
        </tr>
    @foreach ($websites as $website)
    <tr>
        <td class="strong">
            <a href="{{ url('website/show', ['id' => $website->id]) }}">{{ $website->title }}</a>
        </td>
        <td class="text-center">
            <a href="{{ 'http://' . $website->url }}">{{ $website->url }}</a>
        </td>
        <td class="text-right">
            {{ $website->testsCount('enabled') }}
        </td>
        <td class="text-right">
            {{ $website->testsCount() }}
        </td>
        <td class="text-right">
            @if ($website->published_at > 0)
                {{ $website->published_at }}
            @endif
        </td>
        <td class="text-right">
            <div class="btn-group">
            <button data-toggle="dropdown" class="btn btn-default dropdown-toggle">Choose <span class="caret"></span></button>   
            <ul class="dropdown-menu">
                <li>
                    <a href="{{ route('tests.manager', ['id' => $website->id]) }}">
                    <span class="glyphicon glyphicon-education" aria-hidden="true"></span>
                    Manage tests</a>
                </li>
                <li>
                    <a href="{{ url('website/edit', ['id' => $website->id]) }}">
                    <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    Edit</a>
                </li>
                <li>
                    <a href="{{ route('website.stop', ['id' => $website->id]) }}" title="Enable">
                    <span class="glyphicon glyphicon-pause" aria-hidden="true"></span>
                    Pause all tests</a>
                </li>
                <li>
                    <a onclick='confirmLocation("{{ url('website/delete', ['id' => $website->id]) }}", "Deleted websites cannot be recovered. Are you sure?")'>
                    <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    Delete</a>
                </li>
            </ul>
            </div>
        </td>
    </tr>
    @endforeach
    </table>
@endif

@endsection