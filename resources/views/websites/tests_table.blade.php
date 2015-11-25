<table class="table">
        <tr>
            <th class="text-center">Test</th>
            <th class="text-right">Control conv.</th>
            <th class="text-right">Variation conv.</th>
            <th class="text-right">Improvement</th>
            {{--<th class="text-right">Adaptive</th>--}}
            <th class="text-right">Visitors reached</th>
            <th class="text-right">Goal</th>
            <th class="text-right">Updated</th>
            <th class="text-right">Actions</th>        
        </tr>

    @foreach ($tests as $test)
        @if($test->status === 'disabled')
            <tr class="test-disabled" id="test-{{ $test->id }}">
        @else
            <tr id="test-{{ $test->id }}">
        @endif
            <td class="strong">                
                {{ $test->title }}
            </td>
            <td class="text-right">
                {{ $test->originalConv() }} %
            </td>
            <td class="text-right">
                {{ $test->variationConv() }} %
            </td>
            <td class="text-right">
                {{ $test->convChange() }} %
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
                    {{--
                    @if ($test->isEnabled())
                        <li><a href="{{ route('tests.disable', ['id' => $test->id]) }}">Disable</a></li>
                    @elseif ($test->isDisabled())
                        <li><a href="{{ route('tests.enable', ['id' => $test->id]) }}">Enable</a></li>
                    @endif
                    <li class="divider"></li>--}}
                    @if($test->isEnabled())
                    <li><a href="{{ route('tests.disable', ['id' => $test->id]) }}" title="Pause">
                        <span class="glyphicon glyphicon-pause" aria-hidden="true"></span>
                        Pause</a></li>
                    @elseif ($test->isDisabled())
                    <li><a href="{{ route('tests.enable', ['id' => $test->id]) }}" title="Enable">
                        <span class="glyphicon glyphicon-play" aria-hidden="true"></span>
                        Resume</a></li>
                    @endif
                    @if (!$test->isArchived())
                    <li><a href="{{ route('tests.archive', ['id' => $test->id]) }}">
                            <span class="glyphicon glyphicon-book" aria-hidden="true"></span>
                            Archive</a>
                    </li>
                    @else
                        <li><a onclick='confirmLocation("{{ url('tests/archive', ['id' => $test->id]) }}", "Stats will be reset to initial values if you activate this test again. Are you sure?")'>
                                <span class="glyphicon glyphicon-inbox" aria-hidden="true"></span>
                                Activate again</a></li>
                    @endif
                    <li><a onclick='confirmLocation("{{ url('tests/destroy', ['id' => $test->id]) }}", "Deleted tests cannot be recovered. Are you sure?")'><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Delete</a></li>
                </ul>
                </div>
            </td>
        </tr>
    @endforeach
    </table>