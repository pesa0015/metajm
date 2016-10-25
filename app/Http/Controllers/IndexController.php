<?php

namespace App\Http\Controllers;

use App\Http\Requests;

class IndexController extends Controller
{
    public function welcome()
    {
    	return view('index');
    }
}
