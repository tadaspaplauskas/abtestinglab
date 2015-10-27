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
    <tr>
        <th class="text-center">Test</th>
        <th class="text-right">Original conv.</th>
        <th class="text-right">Variation conv.</th>
        <th class="text-right">Improvement</th>
        <th class="text-right">Adaptive</th>
        <th class="text-right">Goal</th>
        <th class="text-right">Updated</th>
        <th class="text-right">Actions</th>        
    </tr>
@foreach ($website->tests as $test)
    @if ($test->enabled)
        <tr class="test-enabled">
    @else
        <tr class="test-disabled">
    @endif
        <td class="strong">
        @if ($test->enabled)
            <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
        @else
            <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
        @endif
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
            {{ $test->updated_at or $test->created_at }}
        </td>
        <!--actions go here-->        
        <td class="text-right">
            <div class="btn-group">
            <button data-toggle="dropdown" class="btn btn-default dropdown-toggle">Choose <span class="caret"></span></button>
            <ul class="dropdown-menu">
                @if ($test->enabled)
                <li><a href="{{ url('tests/disable', ['id' => $test->id]) }}">Disable</a></li>
                @else
                    <li><a href="{{ url('tests/enable', ['id' => $test->id]) }}">Enable</a></li>
                @endif
                  <li class="divider"></li>
                  <li><a href="{{ url('tests/archive', ['id' => $test->id]) }}">Archive</a></li>
                  <li><a href="{{ url('tests/delete', ['id' => $test->id]) }}">Delete</a></li>
            </ul>
            </div>
        </td>
    </tr>
@endforeach
</table>
<hr>
<p>
    <a href="{{ url('website/show/archived', ['id' => $website->id]) }}" class="btn btn-default">See archived tests</a>
</p>
<hr>
<div>
<h2>JS code</h2>
<p>
    Add this JS code in the &lt;head&gt; tag of your website.
    This code should be loaded in every page where you want to conduct tests.
</p>
<p>
    <textarea style="width: 100%" readonly onclick="this.focus();this.select()"><script src="{{ $website->jsUrl() }}"></script></textarea>
</p>
</div>

@endsection