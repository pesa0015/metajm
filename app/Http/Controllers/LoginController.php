<?php

namespace App\Http\Controllers;

use Auth;
use App\CompanyEmployer;
use App\User;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
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

    public function authPrivate(Request $request)
    {
        $email = $request->email;

        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json(['type' => 'email', 'message' => 'E-postadressen hittades inte.']);
        }

        // create our user data for the authentication
        $userdata = array(
            'email'     => $email,
            'password'  => $request->password
        );

        \Config::set('auth.defaults.guard', 'users');

        if (Auth::attempt($userdata)) {
            return response()->json(['success' => true]);
        }
        return response()->json(['type' => 'password', 'message' => 'Fel lösenord.']);
    }

    public function authCompany()
    {
        $email = Input::get('email');

        $user = CompanyEmployer::where('email', Input::get('email'))->first();

        if (!$user) {
            return Redirect::to('login')->withInput()->with('error-email', 'Unknown username.');
        }

        // create our user data for the authentication
        $userdata = array(
            'email'     => $email,
            'password'  => Input::get('password')
        );

        if (Auth::attempt($userdata)) {
            $user = CompanyEmployer::with('company')->find(Auth::user()->id);
            Auth::user()->company->id = $user->company->id;
            Auth::user()->company->name = $user->company->name;
            return Redirect::to('/company/start');
        }
        return Redirect::to('login')->withInput()->with('error-password', 'Wrong password.');
    }
}
