@extends('master')

@section('title', 'Your payments')

@section('content')

<p>
You have <strong>{{ $user->getAvailableResources() }}</strong> unique test views left to use. Do not wait until
you run out - <a href="{{ route('pricing') }} ">buy more now</a>.
</p>

<table class="table">
	<thead>
	<tr>
		<th>Date</th>
		<th>Package</th>
		<th>Sum</th>
	</tr>
	</thead>
	<tbody>
	@foreach($payments as $payment)
		<tr>
			<td>{{ $payment->created_at }}</td>
			<td>{{ $payment->plan }} ({{ $payment->visitors }} visitors)</td>
			<td>{{ $payment->gross }} {{ $payment->currency }}</td>
		</tr>
	@endforeach
	</tbody>
</table>

@endsection