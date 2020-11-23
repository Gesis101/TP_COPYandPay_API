<?php

namespace App\Http\Controllers;

use App\Models\PaymentHistory;
use App\Models\User;
use Carbon\Carbon;
use Carbon\Traits\Date;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;
use mysql_xdevapi\Exception;
use Symfony\Component\Console\Input\Input;


class PaymentController extends Controller
{
    // Curl request to the OPPWA API on each submission
    public function prepareCheckOut(Request $request)
    {
        $amount = $request->input('amount');
        $reference = $request->input('referenceID');
        $url = "https://test.oppwa.com/v1/checkouts";
        $data = "entityId=8ac7a4ca759cd78501759dd759ad02df" .
            "&amount=".$amount.
            "&merchantTransactionId=".$reference.
            "&currency=GBP" .
            "&paymentType=DB";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization:Bearer OGFjN2E0Y2E3NTljZDc4NTAxNzU5ZGQ3NThhYjAyZGR8NTNybThiSmpxWQ=='));
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

        return $responseData;
    }

    public function handlePayment(Request $request)
    {
        $data = $request->input('id', 1);
        $url = "https://test.oppwa.com/v1/checkouts/".$data."/payment";
        $url .= "?entityId=8ac7a4ca759cd78501759dd759ad02df";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization:Bearer OGFjN2E0Y2E3NTljZDc4NTAxNzU5ZGQ3NThhYjAyZGR8NTNybThiSmpxWQ=='));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if(curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        return $responseData;
    }

    //Store payment history to Database using the ORM.
    public function storeData(Request $request){
        try {
            $amount = $request->get('amount', 1);
            $ref = $request->get('reference', 1);
            $result = $request->get('status', 1);
            $id = auth()->id();
            if (!auth()->id()){
                $id = 1;
            }
            $payment = new PaymentHistory;
            $payment->amount = $amount;
            $payment->created_at = Carbon::createFromFormat('Y-m-d H:i:s', now());
            $payment->reference = $ref;
            $payment->result = $result;
            $payment->user_id = $id;
            $payment->save();
        }catch (Exception $e){
            return json_encode($e);
        }

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
