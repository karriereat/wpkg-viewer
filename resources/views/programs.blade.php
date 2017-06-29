@extends('layout.app')

@section('scripts')
	<script src="{{ mix('/js/pages/programs.js') }}"></script>
@stop

@section('content')
	<h1 class="headline">
		<a href="{{ url('/') }}" class="back"><i class="fa fa-arrow-circle-left"></i></a>
		Programs
	</h1>

	<select id="status">
		<option value="-1">All</option>
		@foreach ($packageStatuses as $packageStatus)
			<option value="{{ $packageStatus["index"] }}" style="background-color: {{ $packageStatus["color"] }}">
				{{ $packageStatus["desc"] }}
			</option>
		@endforeach
	</select>

	<? $allProfileIdList = []; ?>

	<table class="hover profiles sortable">
		<thead>
			<td>Hostname</td>
			<td>Name</td>
			<td>Id</td>
			<td>Version</td>
			<td>Status</td>
			<td>Change Date</td>
		</thead>
		<tbody>
			@foreach ($machines as $machine)
				@foreach ($machine->profiles as $packageName => $package)
					@foreach ($package as $packageName => $packageContent)
						<tr style="background-color: {{ $packageContent["status"]["color"] }}" data-status="{{ $packageContent["status"]["index"] }}">
							<td>
								<a href="{{ url('machine/' . strtolower($machine->hostname))  }}"?>
									{{ $machine->hostname }}
								</a>
							</td>
							@if (isset($packageContent["name"]))
								<td>
									{{ $packageContent["name"] }}
								</td>
							@else
								<td></td>
							@endif

							<td class="id">{{ $packageName }}</td>

							@if (isset($packageContent["version"]))
								<td>
									{{ $packageContent["version"] }}
								</td>
							@else
								<td></td>
							@endif

							@if (isset($packageContent["status"]))
								<td>
									{{ $packageContent["status"]["desc"] }}
								</td>
							@else
								<td></td>
							@endif

							@if (is_null($machine->lastUpdate))
								<td class="lastUpdate" sorttable_customkey="-1">
									<i>not set</i>
								</td>
							@else
								<td class="lastUpdate" sorttable_customkey="{{ $machine->lastUpdate }}">
									{{ date("d.m.Y H:i", $machine->lastUpdate) }}
								</td>
							@endif
						</tr>
					@endforeach
				@endforeach
			@endforeach
		</tbody>
	</table>
@stop