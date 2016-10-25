@extends('company')

@section('head')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.5.0/fullcalendar.min.css">
@stop
@section('content')
	<h1>Tider</h1>
	<div id="calendar"></div>
	<div id="times">
		<input type="hidden" id="today" value="<?=date('Y-m-d'); ?>">
		@if ($schedule)
			@for ($i = 0; $i < count($newTimes); $i++)
				<?php $today = date('Y-m-d') . ' ' . $newTimes->$i; ?>
					<?php if (in_array($today, $times)): ?>
					<?php $key = array_search($today, $times); ?>
					<?php if ($schedule->$key->booked == 1): ?>
						<div id="<?=$schedule->$key->id]; ?>" class="timestamp minus booked" value="<?=date('H:i:s', strtotime($schedule->$key->timestamp)); ?>"><?=date('H:i:s', strtotime($schedule>$key->timestamp)); ?> <?=$schedule->$key->first_name . ' ' . $schedule->$key->last_name; ?></div>
					<?php else: ?>
						<div id="<?=$schedule->$key->id']; ?>" class="timestamp minus free" value="<?=date('H:i:s', strtotime($schedule[$key]['timestamp'])); ?>"><?=date('H:i:s', strtotime($schedule[$key]['timestamp'])); ?> <span class="ion-android-remove"></span></div>
					<?php endif; ?>
				<?php else: ?>
				<div class="timestamp plus" value="<?=$newTimes[$i]; ?>"><?=$newTimes[$i]; ?> <span class="ion-ios-plus-outline"></span></div>
			<?php endif; ?>
			@endfor
		@else
			@foreach($newTimes as $newTime)
				<div class="timestamp plus" value="{{ $newTime }}">{{ $newTime }} <span class="ion-ios-plus-outline"></span></div>
			@endforeach
		@endif
	</div>
@stop
@section('footer')
<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="http://momentjs.com/downloads/moment.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/locale/sv.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.5.0/fullcalendar.min.js"></script>
<script src="{{ URL::asset('js/company.times.js') }}"></script>
@stop