<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DateTime;

require 'Svea/Includes.php';

class IndexController extends Controller
{

    public function welcome()
    {
    	return view('index');
    }

    public function check(Request $request)
    {
    	// get config object
        $myConfig = \Svea\SveaConfig::getTestConfig();
        $countryCode = 'SE'; // should match request countryCode
        // the raw request response is posted to the returnurl (this page) from Svea.
        $rawResponse = $_POST;
        // decode the raw response by passing it through the Svea\WebPay\Response\SveaResponse class
        $myResponse = new \SveaResponse( $rawResponse, $countryCode, $myConfig );
        // The decoded response is available through the ->getResponse() method.
        // Check the response attribute 'accepted' for true to see if the request succeeded, if not, see the attributes resultcode and/or errormessage
        if ($myResponse->getResponse()->accepted == 1) {
            $booking_id = \Crypt::decrypt(\Cookie::get('booking'));
            $booking = \App\Booking::with('company')->find($booking_id);

            $booking->payment = 1;
            $booking->transaction_id = $myResponse->getResponse()->transactionId;
            $booking->payment_method = $myResponse->getResponse()->paymentMethod;
            $booking->booking_key = \Cookie::get('booking');          
            $booking->save();
                                
            return view('success', ['time' => new DateTime($booking->start), 'company' => $booking->company]);
        }
        else {
            return redirect('/');
        }
    }
}
