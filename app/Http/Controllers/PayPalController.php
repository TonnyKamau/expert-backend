<?php

namespace App\Http\Controllers;

use App\Models\PayPal;
use Illuminate\Http\Request;
use DB;

class PayPalController extends Controller
{
    public function capture(Request $req){
        //$data = $req->getContent();
        $data = '{
            "id": "95X97974V2837761E",
            "intent": "CAPTURE",
            "status": "COMPLETED",
            "purchase_units": [
                {
                    "reference_id": "default",
                    "amount": {
                        "currency_code": "USD",
                        "value": "400.00"
                    },
                    "payee": {
                        "email_address": "sb-onucq13257391@personal.example.com",
                        "merchant_id": "HNKH6FYHXXDP4"
                    },
                    "description": "cool looking table",
                    "shipping": {
                        "name": {
                            "full_name": "John Doe"
                        },
                        "address": {
                            "address_line_1": "Free Trade Zone",
                            "admin_area_2": "Nairobi",
                            "postal_code": "00521",
                            "country_code": "KE"
                        }
                    },
                    "payments": {
                        "captures": [
                            {
                                "id": "2JX9402695727215L",
                                "status": "COMPLETED",
                                "amount": {
                                    "currency_code": "USD",
                                    "value": "400.00"
                                },
                                "final_capture": true,
                                "seller_protection": {
                                    "status": "ELIGIBLE",
                                    "dispute_categories": [
                                        "ITEM_NOT_RECEIVED",
                                        "UNAUTHORIZED_TRANSACTION"
                                    ]
                                },
                                "create_time": "2022-02-24T12:06:17Z",
                                "update_time": "2022-02-24T12:06:17Z"
                            }
                        ]
                    }
                }
            ],
            "payer": {
                "name": {
                    "given_name": "John",
                    "surname": "Doe"
                },
                "email_address": "sb-rerfs13256769@business.example.com",
                "payer_id": "6H9CQN5FU4XWU",
                "address": {
                    "country_code": "KE"
                }
            },
            "create_time": "2022-02-24T12:05:13Z",
            "update_time": "2022-02-24T12:06:17Z",
            "links": [
                {
                    "href": "https://api.sandbox.paypal.com/v2/checkout/orders/95X97974V2837761E",
                    "rel": "self",
                    "method": "GET"
                }
            ]
        }';
        //get data from the request
        /* $payment_id = $req->payment_id;
        $status = $req->status;
        $amount = $req->amount; */

        $payment_id = uniqid('P');
        $status = rand(0, 1) ? 'CREATED' : 'COMPLETED';
        $amount = 100;

        //generate user id
        $user_id = uniqid('U');
        //save to DB
        $order = new PayPal();
        $order->payment_id = $payment_id;
        $order->user_id = $user_id;
        $order->status = $status;
        $order->amount = $amount;
        $order->payment_method = "Paypal";
        $order->product = "essay";
        $order->discount_id = "WXSRT";
        $order->description = "write_essay_college_6pages";
        $order->other_payment_details = $data;
        if($status == "COMPLETED"){
            $order->save();
            return response('Payment Success', 200);
        }
        else if($status == "CREATED"){
            $order->save();
            return response('Payment Not Completed  '.$payment_id, 202);
        }
        else { return response('Payment Failed', 400); }
    }

}
