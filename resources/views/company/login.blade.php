@extends('company')

@section('content')
	<h1>Logga in</h1>
	{{ Form::open((array('action' => 'LoginController@auth'))) }}
	<p>Mail:</p>
	<input type="mail" name="email">
	<p>Lösenord:</p>
	<input type="password" name="password">
	<input type="submit" name="Logga in">
	{{ Form::close() }}
@stop