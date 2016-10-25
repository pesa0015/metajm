<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>Metajm</title>
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('vendor/bootstrap/bootstrap.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('vendor/ionicons/ionicons.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('vendor/select2/select2.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('vendor/select2/select2-skins.min.css') }}">
	@yield('head')
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('css/company.css') }}">
</head>
<body>
	@if (Auth::user())
		<header>
			<div id="company-name"><span>{{ Auth::user()->company->name }}</span><span><i class="ion-person"></i>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span></div>
			<nav id="nav">
				<a href="/company/todo">Att göra</a>
				<a href="/company/times">Tider</a>
				<a href="/company/services">Hantera tjänster</a>
				<a href="/company/opening_hours">Öppettider</a>
				<a href="/company/profile">Min sida</a>
				<a href="/logout">Logga ut <i class="ion-log-out"></i></a>
			</nav>
		</header>
	@endif
	@yield('content')
	<script src="{{ URL::asset('vendor/jquery/jquery-2.1.4.min.js') }}"></script>
	<script src="{{ URL::asset('vendor/select2/select2.min.js') }}"></script>
	<script src="{{ URL::asset('vendor/noty_js/jquery.noty.packaged.min.js') }}"></script>
	@yield('footer')
	@if(isset($script))
		<script src="{{ URL::asset($script) }}"></script>
	@endif
</body>
</html>