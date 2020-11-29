<?php

namespace App\Http\Controllers;

use App\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
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
            'description' => 'string'
        ]);

        $data = $request->all();
        $category_table = ProductCategory::latest()->first();

        if ($category_table) {
            $category_id = $category_table->id + 1;
        } else {
            $category_id = 1;
        }
        
        $ProductCategory = new ProductCategory;
        $ProductCategory->id = $category_id;
        $ProductCategory->name = $data['name'];
        $ProductCategory->description = !empty($data['description']) ? $data['description'] : "";

        if ($ProductCategory->save()) {
            $ProductCategory->id = $category_id;
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok',
                'data' => $ProductCategory
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Failure to save ProductCategory'
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
        $ProductCategory = ProductCategory::where('id',$id)->get()[0];

        if ($ProductCategory) {
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok',
                'data' => $ProductCategory
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'ProductCategory not found'
            ];

            $this->status = 405;
        }

        return response()->json($this->responsedata,$this->status);
    }


    public function index()
    {
        $ProductCategory = ProductCategory::all();

        if ($ProductCategory) {
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok',
                'data' => $ProductCategory
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'ProductCategory not found'
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
            'description' => 'string'
        ]);

        $data = $request->all();
        
        $ProductCategory = ProductCategory::find($id);
        $ProductCategory->name = $data['name'];
        $ProductCategory->description = !empty($data['description']) ? $data['description'] : $ProductCategory->description;
        
        if ($ProductCategory->save()) {
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok',
                'data' => $ProductCategory
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Failure to save ProductCategory'
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
        if (ProductCategory::where('id',$id)->forceDelete()) {
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok'
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'ProductCategory not found'
            ];

            $this->status = 405;
        }

        return response()->json($this->responsedata,$this->status);
    }
}
