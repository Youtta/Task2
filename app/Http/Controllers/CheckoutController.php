<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe;

class CheckoutController extends Controller
{

    public function charge(Request $request)
    {
        try {
            Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

            $customer = Stripe\Customer::create(array(
                'email' => $request->stripeEmail,
                'source' => $request->stripeToken
            ));

            $charge = Stripe\Charge::create(array(
                'customer' => $customer->id,
                'amount' => 200,
                'currency' => 'usd'
            ));

            return 'Charge successful!';
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }
}
