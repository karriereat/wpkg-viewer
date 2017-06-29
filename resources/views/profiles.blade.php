@extends('layout.app')

@section('content')
	<h1 class="headline">
		<a href="{{ url('/') }}" class="back"><i class="fa fa-arrow-circle-left"></i></a>
		Profiles
	</h1>

	<table class="clickable hover profiles sortable">
		<thead>
			<tr>
				<td>Name</td>
				<td>Amount</td>
			</tr>
		</thead>
		</tbody>
			@foreach ($profiles as $profileName => $profile)
				<tr data-link="{{ url('profile/' . strtolower($profileName)) }}" class="clickableTr">
					<td>{{ $profileName }}</td>
					<td>{{ $profile["amount"] }}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
@stop