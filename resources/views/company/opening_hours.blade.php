@extends('company')

@section('content')
	<h1>Öppettider</h1>
	<p>Ange tiderna du jobbar. Det är dessa som dina kunder kommer att kunna boka.</p>
	<div id="abc">
		<div id="start">Från</div>
		<div id="end">Till</div>
	</div>
	<div id="days">
		@foreach($days as $index => $day)
		<div class="checkbox day">
			<label><input type="checkbox" class="is_open" {{ $days_open[$index] }}>{{ trans('days.' . ucfirst($day)) }}</label>
			<div class="set-hours">
				<div class="start">
					<input type="text" id="start-{{ $day }}" class="form-control opening-hours" value="08:00">
				</div>
				<div></div>
				<div class="end">
					<input type="text" id="end-{{ $day }}" class="form-control opening-hours" value="16:00">
				</div>
			</div>
		</div>
		@endforeach
	</div>
	<div id="settings">
		<div id="settings-left">
			@if($last_day)
				<p id="open-last-day">Du har tider t.o.m. <span id="last-day">{{ $last_day }}</span></p>
			@else
				<p id="open-last-day"><i class="ion-alert" style="margin-right:10px;"></i>Dina arbetstider är inte definierade.</p>
			@endif
			<div class="checkbox">
				<label><input type="checkbox" id="repeat-weeks" checked>Upprepa varje vecka</label>
			</div>
			<label>Ställ in antal veckor:</label><input type="number" min="1" id="weeks" class="form-control" value="4" style="width:60px;">
		</div>
		<div id="settings-right">
			<button type="submit" id="set-opening-hours" class="add-new-service">
				<i class="ion-edit" style="font-size:15px;"></i>
				<span>Spara</span>
			</button>
		</div>
	</div>
@stop

@section('footer')
	<script src="{{ URL::asset('vendor/moment_js/moment.js') }}"></script>
	<script src="{{ URL::asset("vendor/moment_js/locale/$locale.js") }}"></script>
	<script src="{{ URL::asset('js/company.opening_hours.js') }}"></script>
@stop