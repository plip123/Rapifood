<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Payment;
use App\Order;
use App\Extra;
use App\Product;
use App\ExtrasProductOrder;
use App\ProductOrders;

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


    public function calculateAmount ($orderID) {
        $total = 0;
        $Orders = Order::where('id',$orderID)->get()[0];
        if (!empty($Orders)) {   
            $ProductOrders = ProductOrders::where('orderID',$orderID)->get();
            foreach ($ProductOrders as $values) {
                $Product = Product::where('id',$values['productID'])->get();
                $Product = !empty($Product) ? $Product[0] : null;
                if ($Product) {
                    if ($Product->offert == 1) {
                        $total += $Product->offert_price;
                    } else {
                        $total += $Product->price;
                    }
                }
                $ProductOrderExtras = ExtrasProductOrder::where('productOrderID',$values['id'])->get();
                foreach ($ProductOrderExtras as $extra) {
                    $Extra = Extra::where('id',$extra['extraID'])->get();
                    $Extra = count($Extra) > 0 ? $Extra[0] : null;
                    if ($Extra) {
                        $total += $Extra->price * $extra['quantity'];
                    }
                }
            }
        }

        return $total;
    }


    public function index(Request $request) {
        //return $request;
        $this->validate($request, [
            'orderID' => 'required|integer',
            'cardNumber' => 'required|string',
            'cardDate' => 'required|string',
            'securityCode' => 'required|string',
            'cardName' => 'required|string',
            'description' => 'required|string',
        ]);
        
        $amount = $this->calculateAmount($data['orderID']);
        $Payment = Payment::where("active",1);
        $this->apiKey = $Payment->get("apiKey")[0]["apiKey"];
        $this->url = $Payment->get("url")[0]["url"];
        
        $args = array(
            "creditCardNumber" => $request["cardNumber"],
            "creditCardExpirationDate" => $request["cardDate"],
            "creditCardSecurityCode" => $request["securityCode"],
            "creditCardName" => $request["cardName"],
            "amount" => intval($amount),
            "description" => $request["description"],
            "commerce" => $this->commerce,
        );

        $data = json_encode($args);

        //return $data;

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
