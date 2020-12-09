<?php

namespace App\Utilities;

use Illuminate\Support\Facades\Storage;
use App\Order;
use App\Product;
use App\Extra;
use App\ExtrasProductOrder;
use App\ProductOrders;


class OrderResponse
{
    private $id;

    public function __construct($orderID)
    {
        $this->id = $orderID;
    }


    public function calculateAmount () {
        $total = 0;
        $Orders = Order::where('id',$this->id)->get()[0];
        if (!empty($Orders)) {   
            $ProductOrders = ProductOrders::where('orderID',$this->id)->get();
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

    public function getOrderDetails () {
        $Order = Order::where('id',$this->id)->get()[0];
        $products = array();

        if (!empty($Order)) {  
            $ProductOrders = ProductOrders::where('orderID',$this->id)->get();
            $product = array();
            foreach ($ProductOrders as $values) {
                $Product = Product::where('id',$values['productID'])->get();
                $Product = !empty($Product) ? $Product[0] : null;
                if ($Product) {
                    $Product->image = Storage::url($Product->image);
                    $ProductOrderExtras = ExtrasProductOrder::where('productOrderID',$values['id'])->get();
                    $extras = array();
                    foreach ($ProductOrderExtras as $extra) {
                        $Extra = Extra::where('id',$extra['extraID'])->get();
                        $Extra = count($Extra) > 0 ? $Extra[0] : null;
                        if ($Extra) {
                            $Extra->image = Storage::url($Extra->image);
                            array_push($extras, $Extra);
                        }
                    }
                    
                    $ord['product'] = $Product;
                    $ord['extras'] = $extras;
                    array_push($products, $ord);
                }
            }
        }
        $aux['details'] = $products;
        $aux['amount'] = $this->calculateAmount();
        return $aux;
    }
}