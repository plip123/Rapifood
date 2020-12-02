<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\Extra;
use App\Product;

class OrderController extends Controller
{
    private $responsedata;
    private $status;


    public function __construct()
    {
        $this->responsedata = array();
        $this->status = 200;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function priceCalculate ($productID,$extraID,$quantity) {
        $Extras = Extra::where('id',$extraID)->get();
        $Product = Product::where('id',$productID)->get();

        
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'paymentID' => 'required|integer',
            'productID' => 'required|integer',
            'extraID' => 'required|integer',
            'quantity' => 'required|integer'
        ]);

        $data = $request->all();
        $payment_table = Payment::latest()->first();

        if ($payment_table) {
            $payment_id = $payment_table->id + 1;
        } else {
            $payment_id = 1;
        }
        
        $Payment = new Payment;
        $Payment->id = $payment_id;
        $Payment->name = $data['name'];
        $Payment->description = $data['description'];
        $Payment->apiKey = $data['apiKey'];

        if ($Payment->save()) {
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok'
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Failure to save payment'
            ];

            $this->status = 405;
        }

        return response()->json($this->responsedata,$this->status);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Payment = Payment::where('id',$id)->get();

        if ($Payment) {
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok',
                'data' => $Payment
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Payment not found'
            ];

            $this->status = 405;
        }

        return response()->json($this->responsedata,$this->status);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'description' => 'string',
            'apiKey' => 'required|string'
        ]);

        $data = $request->all();
        
        $Payment = Payment::find($id);
        $Payment->name = $data['name'];
        $Payment->description = $data['description'];
        $Payment->apiKey = $data['apiKey'];

        if ($Payment->save()) {
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok',
                'data' => $Payment
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Failure to save payment'
            ];

            $this->status = 405;
        }

        return response()->json($this->responsedata,$this->status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Payment::where('id',$id)->forceDelete()) {
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok'
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Payment not found'
            ];

            $this->status = 405;
        }

        return response()->json($this->responsedata,$this->status);
    }
}