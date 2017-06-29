@extends('layout.app')

@section('content')
	<h1 class="headline">
		<a href="{{ url('machines') }}" class="back"><i class="fa fa-arrow-circle-left"></i></a>
		{{ ucfirst($machine->hostname) }}
	</h1>

	<p>
		@if (is_null($machine->lastUpdate))
			<i>not set</i>
		@else
			Stand: {{ date("d.m.Y H:i", $machine->lastUpdate) }}
		@endif
	</p>

	<h2>System Information</h2>
	<table class="sysInfo">
		<tr>
			<td>
				Operating System
			</td>
			<td>
				{{ ucwords($machine->system["os"]["name"]) }}
			</td>

			<td>
				Version
			</td>
			<td>
				{{ ucwords($machine->system["os"]["version"]) }}
			</td>
		</tr>
		<tr>
			<td>
				Service Pack
			</td>
			<td>
				{{ $machine->system["os"]["sp"] }}
			</td>
			<td>
				Architecture
			</td>
			<td>
				{{ $machine->system["os"]["architecture"] }}
			</td>
		</tr>
		<tr>
			<td>
				Domain
			</td>
			<td>
				{{ $machine->system["domain"] }}
			</td>
			<td>
				Status
			</td>
			<td>
				{{ $machine->status }}
			</td>
		</tr>
	</table>

	<h2>Profiles</h2>
	@foreach($machine->profiles as $profileName => $profile)
		<h3>{{ $profileName }}</h3>
		<table class="sortable">
			<thead>
				<tr>
					<td>Name</td>
					<td>Id</td>
					<td>Version</td>
					<td>Status</td>
				</tr>
			</thead>
			<tbody>
				@foreach($profile as $packageName => $package)
					<tr style="background-color: <?= $package["status"]["color"] ?>">
						@if (isset($package["name"]))
							<td>{{ $package["name"] }}</td>
						@else
							<td></td>
						@endif

						<td>{{ $packageName }}</td>

						@if (isset($package["version"]))
							<td>{{ $package["version"] }}</td>
						@else
							<td></td>
						@endif

						@if (isset($package["status"]))
							<td>{{ $package["status"]["desc"] }}</td>
						@else
							<td></td>
						@endif
					</tr>
				@endforeach
			</tbody>
		</table>
	@endforeach
@stop
