<?php

namespace App\Http\Controllers;

use App\Models\PaymentHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class PaymentController extends Controller
{

    public function submitPayment(Request $request)
    {
        $amount = $request->input('amount');
        $reference = $request->input('referenceID');
        $url = "https://test.oppwa.com/v1/checkouts";
        $data = "entityId=8ac7a4ca759cd78501759dd759ad02df" .
            "&amount=".$amount.
            "&merchantTransactionId=".$reference.
            "&currency=GBP" .
            "&paymentType=DB".
            "&card.number=4200000000000000".
            "&card.expiryMonth=11".
            "&card.expiryYear=2050".
            "&card.cvv=123";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization:Bearer OGE4Mjk0MTc0YjdlY2IyODAxNGI5Njk5MjIwMDE1Y2N8c3k2S0pzVDg='));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        $this->storeData($amount, $reference);
        return response()->json(json_decode($responseData));
    }

    public function storeData($amount, $ref){
        $payment = new PaymentHistory;
        $payment->amount = $amount;
        $payment->reference = $ref;
        $payment->user_id = auth()->id();
        $payment->save();
    }

    //works, just not for api
    public function showUserHistory(){
        $id = Auth::id();
        $history = PaymentHistory::where('user_id', $id)->get();

        return response()->json(json_decode($history));
    }

}
