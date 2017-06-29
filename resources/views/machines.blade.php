@extends('layout.app')

@section('scripts')
	<script src="{{ mix('/js/pages/machines.js') }}"></script>
@stop

@section('content')
	<h1 class="headline">
		<a href="{{ url('/') }}" class="back"><i class="fa fa-arrow-circle-left"></i></a>
		All Machines
	</h1>
	<input type="text" placeholder="NB60" id="search" />

	<select id="profiles">
		<option value="All">All</option>
		@foreach ($profiles as $profileName => $profileAmount)
			<option value="{{ $profileName }}">{{ $profileName }}</option>
		@endforeach

	</select>
	<table class="clickable hover profiles sortable">
		<thead>
			<tr>
				<td>hostname</td>
				<td>profiles</td>
				<td>architecture</td>
				<td>last update</td>
			</tr>
		</thead>
		<tbody>
		@foreach ($machines as $hostname => $machine)
			@if ($machine->status != "host.xml not set")
				<tr data-link="{{ url('/machine/' . strtolower($hostname)) }}" class="clickableTr">
					<td class="hostname">{{ strtolower($hostname) }}</td>
					<td class="profiles">
						@foreach ($machine->profiles as $profileName => $profile)
							<div class="profile">{{ $profileName }}</div>
						@endforeach
					</td>
					<td>{{ $machine->system["os"]["architecture"] }}</td>
					@if (is_numeric($machine->lastUpdate))
						<td sorttable_customkey="{{ $machine->lastUpdate }}">
							{{ date("d.m.Y H:i", $machine->lastUpdate) }}
						</td>
					@else
						<td sorttable_customkey="-1"><i>not set</i></td>
					@endif
				</tr>
			@endif
		@endforeach
		</tbody>
	</table>
@stop