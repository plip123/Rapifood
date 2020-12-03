<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Store;
use App\Product;
use App\ProductIngredient;

class ProductController extends Controller
{
    private $responsedata;
    private $status;

    public function __construct()
    {
        $this->responsedata = array();
        $this->status = 200;
    }

    public function productFilter(Request $request)
    {
        $this->validate($request, [
            'restaurant_id' => 'array',
            'restaurant_id.*' => 'integer',
            'type_id' => 'array',
            'type_id.*' => 'integer',
            'min_price' => 'numeric',
            'max_price' => 'numeric',
            'like' => 'string',
            'super_combo' => 'boolean',
            'destacado' => 'boolean'
        ]);

        $data = $request->all();

        $products = new Product;

        if (isset($data['restaurant_id']) && is_array($data['restaurant_id'])){
            $products = $products->whereIn('storeID', $data['restaurant_id']);
        }

        if (isset($data['type_id']) && is_array($data['type_id'])){
            $products = $products->whereIn('categoryID', $data['type_id']);
        }

        if (isset($data['min_price'])){
            $products = $products->where('price', '>=', $data['min_price']);
        }

        if (isset($data['max_price'])){
            $products = $products->where('price', '<=', $data['max_price']);
        }

        if (isset($data['like'])){
            $products = $products->where('name', 'like', '%' . $data['like'] . '%');
        }

        if (isset($data['super_combo']) && $data['super_combo']){
            $products = $products->where('priority', '=', 3);
        }

        if (isset($data['destacado']) && $data['destacado']) {
            $products = $products->where('priority', '=', 2);
        }

        return $products->get();
    }


    public function productIngredient ($productID, $ingredientID) {
        $product_ingredient_table = Product::latest()->first();

        if ($product_ingredient_table) {
            $product_ingredient_id = $product_ingredient_table->id + 1;
        } else {
            $product_ingredient_id = 1;
        }

        $arrayIngredients = array();

        foreach ($ingredientID as $ingredient) {
            $ProductIngredient = new ProductIngredient;
            $ProductIngredient->id = $product_ingredient_id;
            $ProductIngredient->productID = $productID;
            $ProductIngredient->ingredientID = $ingredient;

            if ($ProductIngredient->save()) {
                array_push($arrayIngredients, $ingredient);
            }
        }
        
        if (count($arrayIngredients) > 0) {
            return $arrayIngredients;
        }

        return false;
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
            'categoryID' => 'required|integer',
            'ingredientID' => 'array',
            'ingredientID.*' => 'integer'
        ]);

        $User = Auth::user();

        $Store = Store::where('userID', $User->id)->get();

        if (count($Store) > 0) {
            $data = $request->all();
            $product_table = Product::latest()->first();

            if ($product_table) {
                $product_id = $product_table->id + 1;
            } else {
                $product_id = 1;
            }
            
            $Product = new Product;
            $Product->id = $product_id;
            $Product->name = $data['name'];
            $Product->price = $data['price'];
            $Product->offert_price = $data['offert_price'];
            $Product->description = $data['description'];
            $Product->state = $data['state'];
            $Product->offert = $data['offert'];
            $Product->image = !empty($request->file('image')) ? Storage::putFile('public/products', $request->file('image')) : "";
            $Product->priority = $data['priority'];
            $Product->categoryID = $data['categoryID'];
            $Product->storeID = $Store[0]->id;

            if ($Product->save()) {
                $Product->id = $product_id;
                if (!empty($data['ingredientID'])) {
                    $product_ingredient = $this->productIngredient($product_id, $data['ingredientID']);
                    if ($product_ingredient) {
                        $Product->ingredientID = $product_ingredient;
                    }
                }
                $Product->image = !empty($Product->image) ? Storage::url($Product->image) : "";
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
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'This user does not have a store'
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
            'categoryID' => 'required|integer',
            'ingredientID' => 'array',
            'ingredientID.*' => 'integer'
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
        $Product->image = !empty($request->file('image')) ? Storage::putFile('public/products', $request->file('image')) : $Product->image;
        $Product->categoryID = $data['categoryID'];
        $Product->priority = $data['priority'];

        if ($Product->save()) {
            if (!empty($data['ingredientID'])) {
                $product_ingredient = $this->productIngredient($product_id, $data['ingredientID']);
                if ($product_ingredient) {
                    $Product->ingredientID = $product_ingredient;
                }
            }
            $Product->image = !empty($Product->image) ? Storage::url($Product->image) : "";
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
