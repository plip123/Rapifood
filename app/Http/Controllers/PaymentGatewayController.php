<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Payment;

class PaymentGatewayController extends Controller
{
    private $responsedata;
    private $status;
    private $card;
    private $url;
    private $apiKey;
    private $commerce;

    public function __construct()
    {
        $this->responsedata = array();
        $this->status = 200;
        $this->url = '';
        $this->card = '';
        $this->apiKey = '';
        $this->commerce = 'Rapifood';
    }

    public function index(Request $request) {
        //return $request;
        $this->validate($request, [
            'paymentID' => 'required|integer',
            'cardNumber' => 'required|string',
            'cardDate' => 'required|string',
            'securityCode' => 'required|string',
            'cardName' => 'required|string',
            'amount' => 'required|integer',
            'description' => 'required|string',
        ]);

        $Payment = Payment::where("id",$request->get('paymentID'));
        $this->apiKey = $Payment->get("apiKey")[0]["apiKey"];
        $this->url = $Payment->get("url")[0]["url"];
        
        $args = array(
            "creditCardNumber" => $request["cardNumber"],
            "creditCardExpirationDate" => $request["cardDate"],
            "creditCardSecurityCode" => $request["securityCode"],
            "creditCardName" => $request["cardName"],
            "amount" => $request["amount"],
            "description" => $request["description"],
            "commerce" => $this->commerce,
        );

        $data = json_encode($args);

        $curl = curl_init($this->url);
        curl_setopt( $curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt( $curl,CURLOPT_POST,true);
        curl_setopt( $curl,CURLOPT_HTTPHEADER, array('Content-type: application/json',"Accept: application/json",'apikey: '.$this->apiKey));
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true);
        $request = curl_exec( $curl );

        if ($request) {
            $this->responsedata = [
                'status' => true,
                'message' => '',
                'data' => $request
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Failure to pay'
            ];

            $this->status = 405;
        }

        return response()->json($this->responsedata,$this->status);
    }
}
