@extends('master')

@section('title', $website->title . ' - test list')

@section('breadcrumbs', Breadcrumbs::render('website', $website))

@section('content')

<div class="row actions-menu">
<div class="col-md-7 text-left">
    <a href="{{ route('tests.manager', ['id' => $website->id]) }}" class="btn btn-primary">Manage tests</a>
    @if ($website->unpublishedChanges() || true)
        <a href="{{ route('tests.publish', ['id' => $website->id]) }}" class="btn btn-default">Publish test changes</a>
    @else
        <a class="btn btn-default disabled">Publish changes</a>
    @endif
    
    @if ($website->published_at > 0)
        <small>Last published: {{ $website->published_at }}</small>
    @endif
</div>
<div class="col-md-5 text-right">
    @if($website->enabled)
        <a href="{{ route('website.disable', ['id' => $website->id]) }}" class="btn btn-default">Disable website</a>
    @else
        <a href="{{ route('website.enable', ['id' => $website->id]) }}" class="btn btn-default">Enable website</a>
    @endif
    <a href="{{ route('website.edit', ['id' => $website->id]) }}" class="btn btn-default">Edit website</a>
    <a href="{{ route('website.delete', ['id' => $website->id]) }}" class="btn btn-danger">Delete website</a>
</div>
</div>


@if ($website->tests->isEmpty())
    <p>Create your first test now!</p>
@else
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
                <li><a href="{{ route('tests.disable', ['id' => $test->id]) }}">Disable</a></li>
                @else
                    <li><a href="{{ route('tests.enable', ['id' => $test->id]) }}">Enable</a></li>
                @endif
                  <li class="divider"></li>
                  <li><a href="{{ route('tests.archive', ['id' => $test->id]) }}">Archive</a></li>
                  <li><a href="{{ route('tests.delete', ['id' => $test->id]) }}">Delete</a></li>
            </ul>
            </div>
        </td>
    </tr>
@endforeach
</table>
@endif
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
    <input type="text" style="width: 100%" readonly onclick="this.focus();this.select()" value='<script src="{{ $website->jsUrl() }}"></script>'>
</p>
</div>

@endsection