<table class="table">
        <tr>
            <th class="text-center">Test</th>
            <th class="text-right">Original conv.</th>
            <th class="text-right">Variation conv.</th>
            <th class="text-right">Improvement</th>
            {{--<th class="text-right">Adaptive</th>--}}
            <th class="text-right">Total visitors reached</th>
            <th class="text-right">Goal</th>
            <th class="text-right">Updated</th>
            <th class="text-right">Actions</th>        
        </tr>

    @foreach ($tests as $test)
        @if ($test->status === 'enabled')
            <tr class="test-enabled">
        @else
            <tr class="test-disabled">
        @endif
            <td class="strong">
        @if ($test->status === 'enabled')
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
            {{--<td class="text-right">
                {{ $test->adaptive }}
            </td>--}}            
            <td class="text-right">
                {{ $test->totalReach() }}
            </td>
            <td class="text-right">
                {{ $test->goal }}
            </td>
            <td class="text-right">
                {{ $test->updated_at or $test->created_at }}
            </td>
            <!--actions go here-->        
            <td class="text-right">
                <div class="btn-group">
                <button data-toggle="dropdown" class="btn btn-default dropdown-toggle">Choose <span class="caret"></span></button>
                <ul class="dropdown-menu">
                    @if ($test->status === 'enabled')
                        <li><a href="{{ route('tests.disable', ['id' => $test->id]) }}">Disable</a></li>
                    @elseif ($test->status === 'disabled')
                        <li><a href="{{ route('tests.enable', ['id' => $test->id]) }}">Enable</a></li>
                    @endif
                    <li class="divider"></li>
                    @if ($test->status === 'enabled' || $test->status === 'disabled')
                        <li><a href="{{ route('tests.archive', ['id' => $test->id]) }}">Archive</a></li>
                    @elseif ($test->status === 'enabled')
                        <li><a href="{{ url('tests/archive', ['id' => $test->id]) }}">Activate again</a></li>
                    @endif
                    <li><a href="{{ url('tests/delete', ['id' => $test->id]) }}">Delete</a></li>
                </ul>
                </div>
            </td>
        </tr>
    @endforeach
    </table>