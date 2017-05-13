<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Booking;
use App\Company;
use App\TimeLeft;
use App\Time;
use App\Category;
use App\Service;
use Auth;
use DB;
use DateTime;

require base_path('Svea/Includes.php');

class BookController extends Controller
{
    public function goToSveaPaymentPage(Request $request)
    {
        $this->validate($request, [
            'company' => 'required|numeric',
            'service' => 'required|numeric',
        ]);

        $companyId = $request->company;
        $serviceId = $request->service;

        \Config::set('auth.defaults.guard', 'users');

        if (!Auth::check()) {
            return response()->json(['success' => false]);
        }

        $company = Company::find($companyId);
        $service = Service::with('category')->find($serviceId);
        $price = (float) $service->price;

        $start = $request->time;

        $today = new DateTime($start);
        $end = new DateTime($start);
        $end = $end->modify("+ {$service->time} min");

        $booking = new Booking;
        $booking->booked_at = \Carbon\Carbon::now();
        $booking->start = $start;
        $booking->end = $end;
        $booking->payment = 0;
        $booking->booked_by_user = Auth::user()->id;
        $booking->service_id = $service->id;
        $booking->company_id = $companyId;
        $booking->employer_id = 1;
        $booking->save();

        Time::where('timestamp', '>=', $today->format('Y-m-d H:i'))
        ->where('timestamp', '<', $end->format('Y-m-d H:i'))
        ->update(['booking_id' => $booking->id]);

        // get config object
        // replace with class holding your merchantid, secretword, et al, adopted from package Config/SveaConfig.php
        $myConfig = \Svea\SveaConfig::getTestConfig();
        // We assume that you've collected the following information about the order in your shop:
        // customer information:
        $customerFirstName = Auth::user()->first_name;
        $customerLastName = Auth::user()->last_name;
        $customerAddress = Auth::user()->address;
        $customerZipCode = Auth::user()->postal_code;
        $customerCity = Auth::user()->city;
        $customerCountry = Auth::user()->country;
        // The customer has bought three items, one "Billy" which cost 700,99 kr excluding vat (25%)
        // and two hotdogs for 5 kr (incl. vat).
        // We'll also need information about the customer country, and the currency used for this order, etc., see below
        // Start the order creation process by creating the order builder object by calling WebPay::createOrder():
        $myOrder = \WebPay::createOrder($myConfig);
        // You then add information to the order object by using the methods in the Svea\CreateOrderBuilder class.
        // For a Card order, the following methods are required:
        $myOrder->setCountryCode("SE"); // customer country, we recommend basing this on the customer billing address
        $myOrder->setCurrency("SEK");   // order currency
        // required - use a not previously sent client side order identifier, i.e. "order #20140519-371"
        $myOrder->setClientOrderNumber("order #".date('c'));
        // You may also chain fluent methods together:
        $myOrder->setCustomerReference("customer #123") // optional - customer reference, as in "customer #123".
                ->setOrderDate(date('c')); // optional - or use an ISO8601 date as produced by i.e. date('c')

        // Then specify the items bought as order rows, using the methods
        // in the Svea\OrderRow class, and adding them to the order:
        $firstBoughtItem = \WebPayItem::orderRow();
        $firstBoughtItem->setAmountExVat($price);
        $firstBoughtItem->setVatPercent(25);
        $firstBoughtItem->setQuantity(1);
        $firstBoughtItem->setDescription("Bokning: " . $company->name);
        $firstBoughtItem->setArticleNumber("ServiceID:" . $service->id);
        // Add firstBoughtItem to order row
        $myOrder->addOrderRow($firstBoughtItem);
        // For card orders the ->addCustomerDetails() method is optional, but recommended, so we'll
        // add what info we have

        // there's also a ::companyCustomer() method, used for non-person entities
        $myCustomerInformation = \WebPayItem::individualCustomer();
        // Set customer information, using the methods from the IndividualCustomer class
        $myCustomerInformation->setName($customerFirstName, $customerLastName);
        // Svea requires an address and a house number
        $sveaAddress = \Svea\Helper::splitStreetAddress($customerAddress);
        $myCustomerInformation->setStreetAddress($sveaAddress[0], $sveaAddress[1]);
        $myCustomerInformation->setZipCode($customerZipCode)->setLocality($customerCity);
        $myOrder->addCustomerDetails($myCustomerInformation);
        // We have now completed specifying the order, and wish
        // to send the payment request to Svea. To do so, we first select a payment method.
        // For card orders, we recommend using the ->usePaymentMethod(PaymentMethod::SVEACARDPAY).
        $myCardOrderRequest = $myOrder->usePaymentMethod(\PaymentMethod::SVEACARDPAY);
        // Then set any additional required request attributes
        // as detailed below. (See Svea\PaymentMethodPayment and Svea\HostedPayment classes for details.)

        // ISO639 language code, i.e. "SV", "EN" etc. Defaults to English.
        $myCardOrderRequest->setCardPageLanguage("SV")
                           ->setReturnUrl(\URL::to('/') . '/booking/done');
        // Get a payment form object which you can use to send the payment request to Svea
        $myCardOrderPaymentForm = $myCardOrderRequest->getPaymentForm();

        $url = 'https://test.sveaekonomi.se/webpay/payment';
        $message = $myCardOrderPaymentForm->rawFields['message'];
        $mac = $myCardOrderPaymentForm->rawFields['mac'];

        $form = '<form name="paymentForm" id="paymentForm" method="post" action="' . $url . '">'
                        . '<input type="hidden" id="merchantid" name="merchantid" value="1200">'
                        . '<input type="hidden" id="message" name="message" value="' . $message . '">'
                        . '<input type="hidden" id="mac" name="mac" value="' . $mac . '">'
                        . '<input type="hidden" id="booking" name="booking" value="' . $booking->id . '">'
                        . '<input type="submit" name="btnSubmit">'
                    . '</form>';

        return response()->json(['success' => true, 'go_to_payment' => $form])
        ->withCookie(cookie('booking', $booking->id, 15));
    }

    public function start(Request $request)
    {
        \Config::set('auth.defaults.guard', 'users');

        if (Auth::check()) {
            return response()->json(['is_logged_in' => true, 'user' => Auth::user()]);
        }

        return response()->json(['is_logged_in' => false]);
    }
}
