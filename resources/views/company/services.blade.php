@extends('company')

@section('head')
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('vendor/select2/select2.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('vendor/select2/select2-skins.min.css') }}">
@stop

@section('content')
<div id="existing_services">
	<h1>Hantera tjänster</h1>
	@if ($services)
		<table id="services">
			<tr>
				@if (Auth::user()->admin_role == 1)
					<th>Ändra</th>
				@endif
				<th>Tjänst</th>
				<th>Kategori</th>
				<th>Pris (sek)</th>
				<th>Tid (h)</th>
				<th>Använd</th>
			</tr>
			@foreach ($services as $service)
			<tr>
				@if (Auth::user()->admin_role == 1)
					<td>
						<i class="ion-edit edit-service-btn" data-service="{{ $service->id }}"></i>
						<button id="edit-service-{{ $service->id }}">Ok</button>
					</td>
				@endif
				<td>
					<span class="service-{{ $service->id }}">{{ $service->category->name }} </span>
					<input type="text" class="edit-service-{{ $service->id }}" value="{{ $service->category->id }}" data-name="{{ $service->category->name }}">
				</td>
				<td>
					<span class="service-{{ $service->id }}">{{ $service->name }}</span>
					<input type="text" class="edit-service-{{ $service->id }} form-control" value="{{ $service->name }}">
				</td>
				<td>
					<span class="service-{{ $service->id }}">{{ $service->price }}</span>
					<input type="text" class="edit-service-{{ $service->id }} form-control" value="{{ $service->price }}">
				</td>
				<td>
					<span class="service-{{ $service->id }}">{{ $service->time }}</span>
					<select class="edit-service-{{ $service->id }} form-control">
						@foreach($selectTimes as $time)
							@if ($time == $service->time)
								<option value="{{ $time }}" selected>{{ $time }}</option>
							@else
								<option value="{{ $time }}">{{ $time }}</option>
							@endif
						@endforeach
					</select>
				</td>
				@if ($my_services)
					@if (in_array($service->id, $myServicesArray))
						<td><input type="checkbox" class="service-checkbox" data-id="{{ $service->id }}" checked></td>
					@else
						<td><input type="checkbox" class="service-checkbox" data-id="{{ $service->id }}"></td>
					@endif
				@else
						<td><input type="checkbox" class="service-checkbox" data-id="{{ $service->id }}"></td>
				@endif
			</tr>
			@endforeach
		</table>
		@else
			<p>Ni har inga tjänster.</p>
		@endif
		<div>
			@if (Auth::user()->admin_role == 1)
				<span id="add-new-service" class="add-new-service">
					<i class="ion-ios-plus-outline service"></i>
					<i class="ion-ios-plus service"></i>
					<span>Lägg till tjänst</span>
				</span>
				<button type="submit" id="update-services" class="add-new-service" style="display:none;">
					<i class="ion-ios-checkmark-outline service"></i>
					<i class="ion-ios-checkmark service"></i>
					<span>Uppdatera</span>
				</button>
			@endif
		</div>
		<table id="new-services"></table>
</div>
<div id="add-service-instruction">
	<h3>Hur gör man?</h3>
	<div>Använd en tjänst</div>
	<p>När du kryssar i <input type="checkbox"> -check-knappen läggs den tjänsten till din <span class="italic">personliga</span> lista över tjänster som du erbjuder som anställd. Tjänsten blir alltså synlig och bokningsbar på startsidan.</p>
	<div>Ta bort en tjänst</div>
	<p>Om du inte vill erbjuda en tjänst kan du ta bort den genom att bocka ur <input type="checkbox" checked> -rutan.<br />Obs! Tjänsten kommer fortfarande finnas kvar för företaget, dvs dina kolleger kommer kunna använda den.</p>
	<div>Lägg till ny tjänst (endast administratör)</div>
</div>
@stop

@section('footer')
	<script src="{{ URL::asset('vendor/select2/select2.min.js') }}"></script>
	@if (Auth::user()->admin_role == 1)
		<script src="{{ URL::asset('js/company.services.admin.js') }}"></script>
	@else
		<script src="{{ URL::asset('js/company.services.js') }}"></script>
	@endif
@stop