<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Order;
use App\ExtraProductOrder;
use App\ProductOrder;

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
            'paymentID' => 'integer',
            'quantity' => 'required|integer',
            'products' => 'required|array',
            'products.*' => 'required'
        ]);

        $data = $request->all();
        $order_table = Order::latest()->first();
        $User = Auth::user();

        if ($order_table) {
            $order_id = $order_table->id + 1;
        } else {
            $order_id = 1;
        }
        
        $Order = new Order;
        $Order->id = $order_id;
        $Order->userID = $User->id;
        $Order->paymentID = !empty($data['paymentID']) ? $data['paymentID'] : 0;
        $Order->state = 'In process';

        if ($Order->save()) {
            $Order->id = $order_id;
            foreach ($data['products'] as $product) {
                $ProductOrder = new ProductOrder;
                $ProductOrder->id = $order_id;
                $ProductOrder->productID = $product['id'];
                $ProductOrder->orderID = $order_id;

                if ($ProductOrder->save()) {
                    $productOrder_table = ExtraProductOrder::latest()->first();
                    if ($productOrder_table) {
                        $productOrder_id = $productOrder_table->id + 1;
                    } else {
                        $productOrder_id = 1;
                    }
                    $ProductOrder->id = $productOrder_id;
                    foreach ($product['extras'] as $extraID) {
                        $extraProductOrder = ExtraProductOrder::latest()->first();
                        if ($extraProductOrder) {
                            $extraOrder_id = $extraProductOrder->id + 1;
                        } else {
                            $extraOrder_id = 1;
                        }
                        $ExtraProductOrder = new ExtraProductOrder;
                        $ExtraProductOrder->extraID = $extraID;
                        $ExtraProductOrder->productOrderID = $extraOrder_id;
                        $ProductOrder->save();
                    }
                } else {
                    $this->responsedata = [
                        'error'=> ['Failed'],
                        'status' => false,
                        'message' => 'Failure to save Order'
                    ];
        
                    $this->status = 405;
                    return response()->json($this->responsedata,$this->status);
                }
            }
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok',
                'data' => $Order
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Failure to save Order'
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
        $Order = Order::where('id',$id)->get();

        if ($Order) {
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok',
                'data' => $Order
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Order not found'
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
        
        $Order = Order::find($id);
        $Order->name = $data['name'];
        $Order->description = $data['description'];
        $Order->apiKey = $data['apiKey'];

        if ($Order->save()) {
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok',
                'data' => $Order
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Failure to save Order'
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
        if (Order::where('id',$id)->forceDelete()) {
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok'
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Order not found'
            ];

            $this->status = 405;
        }

        return response()->json($this->responsedata,$this->status);
    }
}
