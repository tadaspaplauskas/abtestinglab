<table class="table tests-table">
        <tr>
            <th class="text-center">Test</th>
            <th class="text-right">Control conv.</th>
            <th class="text-right">Variation conv.</th>
            <th class="text-right" title="Relative change is provided.">
                Change
                <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>
            </th>
            {{--<th class="text-right">Adaptive</th>--}}
            <th class="text-right" title="Ususally in statistics 95% is a sufficient significance level, but it's up to you to decide. 95% means that if you repeated the same test 100 times, the same results would be expected at least 95 times.">
                Significance
                <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>
            </th>
            <th class="text-right">Visitors reached/Goal</th>
            <th class="text-right">Updated</th>
            <th class="text-right">Actions</th>
        </tr>

    @foreach ($tests as $test)
        <?php
            $status = $test->status;
            $controlConv = $test->originalConv();
            $variationConv = $test->variationConv();
            $convDiff = $test->convDiff();
            $reach = $test->totalReach();
            $goal = $test->goal;
            $significance = $test->calculateSignificance();
        ?>
        @if($status === 'disabled')
            <tr class="test-disabled" id="test-{{ $test->id }}">
        @else
            <tr id="test-{{ $test->id }}">
        @endif
            <td class="title">
                <strong>{{ $test->title }}</strong>
                <div class="test-conclusion">
                    @if($reach < $goal)
                        The test is still running, have patience.
                    @else
                        @if ($convDiff > 0 && $significance > 90)
                            The variation looks promising.
                        @elseif ($convDiff < 0 && $significance > 90)
                            The variation underperformed. Back to the drawing board!
                        @elseif ($convDiff > 0 && $significance < 90)
                            The results are positive but inconclusive. Consider expanding the test reach.
                        @elseif ($convDiff < 0 && $significance < 90)
                            The results are negative but inconclusive. Consider expanding the test reach.
                        @endif
                    @endif
                </div>
            </td>
            <td class="text-right">
                {{ $controlConv }} % ({{ $test->original_conversion_count}})
            </td>
            <td class="text-right">
                {{ $variationConv }} % ({{ $test->variation_conversion_count}})
            </td>
            <td class="text-right {{ ($convDiff < 0) ? 'bad' : (($convDiff > 0) ? 'good' : '') }}">
                {{-- {{ $convDiff }} % / --}}
                {{ $test->convChange() }} %
            </td>
            {{--<td class="text-right">
                {{ $test->adaptive }}
            </td>--}}
            <td class="text-right {{ (($significance < 90) ? 'bad' : (($significance < 95) ? 'okay' : 'good')) }}">
                {{ $test->calculateSignificance() }} %
            </td>
            <td class="text-right">
                {{ $reach }}/{{ $goal }}
            </td>
            <td class="text-right">
                {{ $test->updated_at->diffForHumans() }}
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
                        <li><a onclick='confirmLocation("{{ route('tests.archive', ['id' => $test->id]) }}", "Stats will be reset to initial values if you activate this test again. Are you sure?")'>
                                <span class="glyphicon glyphicon-inbox" aria-hidden="true"></span>
                                Activate again</a></li>
                    @endif
                    <li><a onclick='confirmLocation("{{ route('tests.destroy', ['id' => $test->id]) }}", "Deleted tests cannot be recovered. Are you sure?")'><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Delete</a></li>
                </ul>
                </div>
            </td>
        </tr>
    @endforeach
    </table>