@extends('master')

@section('title', $website->title)

@section('content')

<div class="row actions-menu">
<div class="col-md-6 text-left">
    <a href="{{ url('tests/manager', ['id' => $website->id]) }}" class="btn btn-primary">Manage tests</a>
    @if ($website->unpublishedChanges() || true)
        <a href="{{ url('tests/publish', ['id' => $website->id]) }}" class="btn btn-default">Publish test changes</a>
    @else
        <a class="btn btn-default disabled">Publish changes</a>
    @endif
    
    @if ($website->published_at > 0)
        Last published: {{ $website->published_at }}
    @endif
</div>
<div class="col-md-6 text-right">
    @if($website->enabled)
        <a href="{{ url('website/disable', ['id' => $website->id]) }}" class="btn btn-default">Disable website</a>
    @else
        <a href="{{ url('website/enable', ['id' => $website->id]) }}" class="btn btn-default">Enable website</a>
    @endif
    <a href="{{ url('website/edit', ['id' => $website->id]) }}" class="btn btn-default">Edit website</a>
    <a href="{{ url('website/delete', ['id' => $website->id]) }}" class="btn btn-danger">Delete website</a>
</div>
</div>

<table class="table">
    <td class="text-center">Test</td>
    <td class="text-right">Original conv.</td>
    <td class="text-right">Variation conv.</td>
    <td class="text-right">Improvement</td>
    <td class="text-right">Adaptive</td>
    <td class="text-right">Goal</td>
    <td class="text-right">Active</td>
    <td class="text-right">Updated</td>
        
    </th>
@foreach ($website->tests as $test)
    @if ($test->enabled)
        <tr class="test-enabled">
    @else
        <tr class="test-disabled">
    @endif
        <td class="strong">
            {{ $test->title }}
        </td>
        <td class="text-right">
            {{ $test->originalConv() }}
        </td>
        <td class="text-right">
            {{ $test->variationConv() }}
        </td>
        <td class="text-right">
            {{ $test->convChange() }}
        </td>
        <td class="text-right">
            {{ $test->adaptive }}
        </td>
        <td class="text-right">
            {{ $test->goal }} {{ $test->goal_type }}
        </td>
        <td class="text-right">
            @if ($test->enabled)
                <a href="{{ url('test/disable', ['id' => $test->id]) }}" class="btn btn-default">Disable</a>
            @else
                <a href="{{ url('test/enable', ['id' => $test->id]) }}" class="btn btn-default">Enable</a>
            @endif
        </td>
        <td class="text-right">
            {{ $test->updated_at or $test->created_at }}
        </td>
    </tr>
@endforeach
</table>
<hr>
<div>
<h2>JS code</h2>
<p>
    Add this JS code in the &lt;head&gt; tag of your website.
    This code should be loaded in every page where you want to conduct tests.
</p>
<p>
    <textarea style="width: 100%" readonly onclick="this.focus();this.select()"><script src="{{ $jsUrl }}"></script></textarea>
</p>
</div>

@endsection