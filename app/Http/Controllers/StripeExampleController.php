<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StripeExampleController extends Controller
{
    public function form(){
        return view('form');
    }


    public function charge(Request $request){
    // Set your Stripe API key.
    \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

    // Get the payment amount and email address from the form.
    $amount = $request->input('amount') * 100;
    $email = $request->input('email');

    // Create a new Stripe customer.
    $customer = \Stripe\Customer::create([
        'email' => $email,
        'source' => $request->input('stripeToken'),
    ]);

    // Create a new Stripe charge.
    $charge = \Stripe\Charge::create([
        'customer' => $customer->id,
        'amount' => $amount,
        'currency' => 'usd',
    ]);

    // Display a success message to the user.
    return 'Payment successful!';
    }
}
