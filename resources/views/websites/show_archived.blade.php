@extends('master')

@section('title', 'Archive: ' . $website->title)

@section('content')

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
@foreach ($website->archivedTests as $test)
        <tr class="test-disabled">
        <td class="strong">
        <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
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
                <li><a href="{{ url('tests/archive', ['id' => $test->id]) }}">Activate again</a></li>
                <li><a href="{{ url('tests/delete', ['id' => $test->id]) }}">Delete</a></li>
            </ul>
            </div>
        </td>
    </tr>
@endforeach
</table>
<hr>
<p>
    <a href="{{ url('website/show', ['id' => $website->id]) }}" class="btn btn-default">See active tests</a>
</p>

@endsection