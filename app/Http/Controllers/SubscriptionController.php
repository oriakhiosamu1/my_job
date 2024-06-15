<?php

namespace App\Http\Controllers;
use App\Http\Middleware\donotAllowUserToMakePayment;
use App\Http\Middleware\isEmployer;
use App\Mail\PurchaseMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use RealRashid\SweetAlert\Facades\Alert;

class SubscriptionController extends Controller
{
    const weekly_amount = 20;
    const monthly_amount = 80;
    const yearly_amount = 200;
    const currency = 'USD';


    public function subscribe(){
        return view('subscription.index');
    }

    public function initiatePayment(Request $request){
        $plans = [
            'weekly' => [
                'name' => 'weekly',
                'description' => 'weekly payment',
                'amount' => self::weekly_amount,
                'currency' => self::currency,
                'quantity' => 1
            ],

            'monthly' => [
                'name' => 'monthly',
                'description' => 'monthly payment',
                'amount' => self::monthly_amount ,
                'currency' => self::currency,
                'quantity' => 1
            ],

            'yearly' => [
                'name' => 'yearly',
                'description' => 'yearly payment',
                'amount' => self::yearly_amount,
                'currency' => self::currency,
                'quantity' => 1
            ]
        ];

        // Stripe::setApiKey(config('services.stripe.secret'));

        try {
            // get the type of plan user selected
            $selectPlan = null;

            // checks the option user clicked
            if($request->is('pay/weekly')) {
                $selectPlan = $plans['weekly'];
                $billingEnds = now()->addWeek()->startOfDay()->toDateString();

            }elseif($request->is('pay/monthly')) {
                $selectPlan = $plans['monthly'];
                $billingEnds = now()->addMonth()->startOfDay()->toDateString();

            }elseif($request->is('pay/yearly')) {
                $selectPlan = $plans['yearly'];
                $billingEnds = now()->addYear()->startOfDay()->toDateString();

            }

            if($selectPlan) {
                $successURl = URL::signedRoute('payment.success',[
                    'plan' => $selectPlan['name'],
                    'billing_ends' => $billingEnds
                ]);

                // dd($successURl);

                $session = Session::create([
                    'payment_method_types' => ['card'],
                    'line_items' => [
                        [
                            'name' => $selectPlan['name'],
                            'description' => $selectPlan['description'],
                            'amount' => $selectPlan['amount']*100,
                            'currency' => $selectPlan['currency'],
                            'quantity' => $selectPlan['quantity']
                        ]
                    ],
                    'success_url' => $successURl,
                    'cancel_url' => route('payment.fail')
                ]);

                return redirect($session->url);
            }

        } catch (\Exception $e) {
            return response()->json($e);
        }

    }

    // when payment is successful
    public function paymentSuccessful(Request $request){
        // update database
        $plan = $request->plan;
        $billing_ends = $request->billing_ends;

        User::where('id', auth()->user()->id)->update([
            'plan' => $plan,
            'billing_ends' => $billing_ends,
            'status' => 'paid'
        ]);

        try{
            Mail::to(auth()->user()->email)->queue(new PurchaseMail($plan, $billing_ends));
        }catch(\Exception $e){
            return response()->json($e);
        }

        Alert::success("Success", 'Payment was successful');
        return redirect()->route('dashboard');
    }

    public function paymentFailed(){
        // redirect

        Alert::error("Error", 'Payment was unsuccessful');
        return redirect()->route('dashboard');
    }

}
