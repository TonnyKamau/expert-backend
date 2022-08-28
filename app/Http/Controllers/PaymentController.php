<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Cashier\Exceptions\IncompletePayment;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        return view('auth.payment.form',[
    'intent' => $user->createSetupIntent()
]);
    }

    public function paymentAction(Request $request)
    {
        $user = Auth::user();
        $paymentMethod = $request->pmethod;

        try {
            $user->createOrGetStripeCustomer();
            $user->updateDefaultPaymentMethod($paymentMethod);
            $user->charge(100, $paymentMethod );
        } catch (IncompletePayment $exception) {
            return redirect()->route(
                'cashier.payment',
                [$exception->payment->id, 'redirect' => route('payment')]
            );
        }


    }
}
