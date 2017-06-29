@extends('layout.app')

@section('scripts')
	<script src="{{ url('/js/pages/profile.js') }}"></script>
@stop

@section('content')
	<h1 class="headline">
		<a href="{{ url('/profiles') }}" class="back"><i class="fa fa-arrow-circle-left"></i></a>
		<?= $profilesName ?>
	</h1>
	@foreach ($profile["packages"] as $packageName => $package)
		<h2>{{ $packageName }}</h2>
		<table class="clickable">
			<thead>
				<tr>
					<td>status</td>
					<td>amount</td>
				</tr>
			</thead>
			<tbody>
				@foreach ($package as $statusName => $machines)
					<tr class="openDetails" data-statusName="{{ $statusName }}">
						<td>{{ $statusName }}</td>
						<td>{{ count($machines) }}</td>
					</tr>
					<tr class="details">
						<td colspan="2">
							<table class="sortable hover clickable">
								<thead>
									<tr>
										<td>
											hostname
										</td>
										<td>
											last change
										</td>
									</tr>
								</thead>
								<tbody>
									@foreach($machines as $machine)
										<tr data-link="{{ url('machine/' . strtolower($machine["hostname"])) }}" class="clickableTr">
											<td class="hostname">{{ $machine["hostname"] }}</td>
											@if (is_null($machine["lastUpdate"]))
												<td sorttable_customkey="-1">
													<i>not set</i>
												</td>
											@else
												<td sorttable_customkey="{{ $machine["lastUpdate"] }}">
													{{ date("d.m.Y H:i", $machine["lastUpdate"]) }}
												</td>
											@endif
										</tr>
									@endforeach
								</tbody>
							</table>
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	@endforeach
@stop