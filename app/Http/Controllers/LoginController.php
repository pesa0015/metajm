<?php

namespace App\Http\Controllers;

use Auth;
use App\companies_employers;
use App\User;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller
{
	public function logout()
	{
		Auth::logout();
		return redirect('/');
	}

	public function loginPrivate()
	{
		return view('auth.login_private');
	}

	public function loginCompany()
	{
		return view('auth.login_company');
	}

	public function authPrivate()
	{
		$email = Input::get('email');

		$user = companies_employers::where('email', Input::get('email'))->first();

		if(!$user) {
			return Redirect::to('login')->withInput()->with('error-email', 'Unknown username.'); 
		}

		// create our user data for the authentication
		$userdata = array(
		    'email'     => $email,
		    'password'  => Input::get('password')
		);

		if (Auth::attempt($userdata)) {
			return Redirect::to('/konto');
		}
		return Redirect::to('login')->withInput()->with('error-password', 'Wrong password.');
	}

	public function authCompany()
	{
		$email = Input::get('email');

		$user = companies_employers::where('email', Input::get('email'))->first();

		if(!$user) {
			return Redirect::to('login')->withInput()->with('error-email', 'Unknown username.'); 
		}

		// create our user data for the authentication
		$userdata = array(
		    'email'     => $email,
		    'password'  => Input::get('password')
		);

		if (Auth::attempt($userdata)) {
			$user = companies_employers::with('company')->find(Auth::user()->id);
			Auth::user()->company->id = $user->company->id;
			Auth::user()->company->name = $user->company->name;
			return Redirect::to('/company/start');
		}
		return Redirect::to('login')->withInput()->with('error-password', 'Wrong password.');
	}
}
