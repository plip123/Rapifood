<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Order;
use App\Extra;
use App\Product;
use App\ExtrasProductOrder;
use App\ProductOrders;

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

    public function priceCalculate ($order) {
        $total = 0;

        if ($order) {
            foreach ($order as $values) {
                $Product = Product::where('id',$values['id'])->get();
                $Product = !empty($Product) ? $Product[0] : null;
                if ($Product) {
                    if ($Product->offert == 1) {
                        $total += $Product->offert_price;
                    } else {
                        $total += $Product->price;
                    }
                }

                foreach ($values['extras'] as $extra) {
                    $Extra = Extra::where('id',$extra['id'])->get();
                    $Extra = count($Extra) > 0 ? $Extra[0] : null;
                    if ($Extra) {
                        $total += $Extra->price * $extra['quantity'];
                    }
                }
            }
        }

        return $total;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'paymentID' => 'integer',
            'products' => 'required|array',
            /*'products.id' => 'required|integer',
            'products.extras' => 'required|array',
            'products.extras.id' => 'integer',
            'products.extras.quantity' => 'integer',*/
        ]);

        $data = $request->all();
        $price = $this->priceCalculate($data["products"]);
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
                $productOrder_table = ProductOrders::latest()->first();
                if ($productOrder_table) {
                    $productOrder_id = $productOrder_table->id + 1;
                } else {
                    $productOrder_id = 1;
                }

                $ProductOrder = new ProductOrders;
                $ProductOrder->id = $productOrder_id;
                $ProductOrder->productID = $product['id'];
                $ProductOrder->orderID = $order_id;

                if ($ProductOrder->save()) {
                    $ProductOrder->id = $productOrder_id;
                    foreach ($product['extras'] as $extra) {
                        $extraProductOrder_table = ExtrasProductOrder::latest()->first();
                        if ($extraProductOrder_table) {
                            $extraProductOrder_id = $extraProductOrder_table->id + 1;
                        } else {
                            $extraProductOrder_id = 1;
                        }

                        $ExtraProductOrder = new ExtrasProductOrder;
                        $ExtraProductOrder->id = $extraProductOrder_id;
                        $ExtraProductOrder->extraID = $extra['id'];
                        $ExtraProductOrder->productOrderID = $productOrder_id;
                        $ExtraProductOrder->quantity = $extra['quantity'];
                        $ExtraProductOrder->save();
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
            $Order->totalAmount = $price;
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
            'paymentID' => 'integer',
            'state' => 'string',
            'products' => 'required|array',
            /*'products.id' => 'required|integer',
            'products.extras' => 'required|array',
            'products.extras.id' => 'integer',
            'products.extras.quantity' => 'integer',*/
        ]);

        $data = $request->all();
        $price = $this->priceCalculate($data["products"]);  
        
        $Order = Order::where('id',$id)->get()[0];
        $Order->paymentID = !empty($data['paymentID']) ? $data['paymentID'] : 0;
        $Order->state = $data['state'];

        if ($Order->save()) {
            $ProductOrder = ProductOrders::where('orderID',$id);
            $ProductOrders = $ProductOrder->get();
            foreach ($ProductOrders as $productOrder) {
                $ExtrasProductOrder = ExtrasProductOrder::where('productOrderID',$productOrder['id'])->forceDelete();
            }
            $ProductOrder->forceDelete();

            foreach ($data['products'] as $product) {
                $productOrder_table = ProductOrders::latest()->first();
                if ($productOrder_table) {
                    $productOrder_id = $productOrder_table->id + 1;
                } else {
                    $productOrder_id = 1;
                }

                $ProductOrder = new ProductOrders;
                $ProductOrder->id = $productOrder_id;
                $ProductOrder->productID = $product['id'];
                $ProductOrder->orderID = $order_id;

                if ($ProductOrder->save()) {
                    $ProductOrder->id = $productOrder_id;
                    foreach ($product['extras'] as $extra) {
                        $extraProductOrder_table = ExtrasProductOrder::latest()->first();
                        if ($extraProductOrder_table) {
                            $extraProductOrder_id = $extraProductOrder_table->id + 1;
                        } else {
                            $extraProductOrder_id = 1;
                        }

                        $ExtraProductOrder = new ExtrasProductOrder;
                        $ExtraProductOrder->id = $extraProductOrder_id;
                        $ExtraProductOrder->extraID = $extra['id'];
                        $ExtraProductOrder->productOrderID = $productOrder_id;
                        $ExtraProductOrder->quantity = $extra['quantity'];
                        $ExtraProductOrder->save();
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
            $Order->totalAmount = $price;
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
        $Order = Order::where('id',$id);
        $Orders = $Order->get();
        if (!empty($Orders)) {   
            foreach ($Orders as $order) {
                $ProductOrder = ProductOrders::where('orderID',$order['id']);
                $ProductOrders = $ProductOrder->get();
                foreach ($ProductOrders as $productOrder) {
                    $ExtrasProductOrder = ExtrasProductOrder::where('productOrderID',$productOrder['id'])->forceDelete();
                }
                $ProductOrder->forceDelete();
            }

            if ($Order->forceDelete()) {
                $this->responsedata = [
                    'status' => true,
                    'message' => 'Ok'
                ];
            }
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
