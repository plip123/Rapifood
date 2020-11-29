<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Product;

class ProductController extends Controller
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
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'price' => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
            'offert_price' => 'regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
            'description' => 'string',
            'state' => 'required|string',
            'offert' => 'required|boolean',
            'image' => 'file',
            'priority' => 'required|integer',
            'storeID' => 'required|integer'
        ]);

        $data = $request->all();
        $product_table = Product::latest()->first();

        if ($product_table) {
            $product_id = $product_table->id + 1;
        } else {
            $product_id = 1;
        }

        $path = Storage::putFile('public/products', $request->file('image'));
        
        $Product = new Product;
        $Product->id = $product_id;
        $Product->name = $data['name'];
        $Product->price = $data['price'];
        $Product->offert_price = $data['offert_price'];
        $Product->description = $data['description'];
        $Product->state = $data['state'];
        $Product->offert = $data['offert'];
        $Product->image = $path;
        $Product->priority = $data['priority'];
        $Product->storeID = $data['storeID'];

        if ($Product->save()) {
            $Product->id = $product_id;
            $Product->image = Storage::url($path);
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok',
                'data' => $Product,
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Failure to save product'
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
        $Product = Product::where('id',$id)->get()[0]   ;

        if ($Product) {
            $Product->image = Storage::url($Product->image);
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok',
                'data' => $Product
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Product not found'
            ];

            $this->status = 405;
        }

        return response()->json($this->responsedata,$this->status);
    }


    public function index()
    {
        $Product = Product::all();

        if ($Product) {
            $responseProduct = array();
            foreach ($Product as $product) {
                $product->image = Storage::url($product->image);
                array_push($responseProduct, $product);
            }

            $this->responsedata = [
                'status' => true,
                'message' => 'Ok',
                'data' => $responseProduct
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Product not found'
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
            'price' => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
            'offert_price' => 'regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
            'description' => 'string',
            'state' => 'required|string',
            'offert' => 'required|boolean',
            'image' => 'file',
            'priority' => 'required|integer',
        ]);

        $data = $request->all();
        
        $Product = Product::find($id);
        $Product->name = $data['name'];
        $Product->price = $data['price'];
        $Product->offert_price = $data['offert_price'];
        $Product->description = $data['description'];
        $Product->state = $data['state'];
        $Product->offert = $data['offert'];
        Storage::delete($Product->image);
        $path = Storage::putFile('public/products', $request->file('image'));
        $Product->image = $path;
        $Product->priority = $data['priority'];

        if ($Product->save()) {
            $Product->image = Storage::url($path);
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok',
                'data' => $Product
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Failure to save Product'
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
        $Product = Product::where('id',$id)->get()[0];
        $path = $Product->image;

        if ($Product->forceDelete()) {
            Storage::delete($path);
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok'
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Product not found'
            ];

            $this->status = 405;
        }

        return response()->json($this->responsedata,$this->status);
    }
}
