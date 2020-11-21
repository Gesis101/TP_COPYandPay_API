<?php

namespace App\Http\Controllers;

use App\Models\PaymentHistory;
use App\Models\User;
use Carbon\Carbon;
use Carbon\Traits\Date;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class PaymentController extends Controller
{
    // Curl request to the OPPWA API on each submission
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
        $responseData = response()->json(json_decode($responseData));
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        //echo json_encode($responseData->original->result->description);
        if(str_contains(json_encode($responseData->original->result->description), 'successfully')){ //If response includes 'success', set the result to true.. vice versa.
            $this->storeData($amount, $reference, true);
        }else{
            $this->storeData($amount, $reference, false);
        }
        return $responseData;
    }

    //Store payment history to Database using the ORM.
    public function storeData($amount, $ref, $result){
        $payment = new PaymentHistory;
        $payment->amount = $amount;
        $payment->created_at = Carbon::createFromFormat('Y-m-d H:i:s', now());
        $payment->reference = $ref;
        $payment->result = $result;
        $payment->user_id = auth()->id();
        $payment->save();
    }

    //Gets the ID of the session user, calls one to many & returns json response.
    public function showUserHistory(){
        $id = Auth::id();
        $history = PaymentHistory::where('user_id', $id)->get();

        return response()->json(json_decode($history));
    }

    //
    public function convertDate($data)
    {
        return Carbon::createFromFormat('m/d/Y', $data)->format('Y-m-d');
    }

}
