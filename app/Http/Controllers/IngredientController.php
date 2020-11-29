<?php

namespace App\Http\Controllers;

use App\Ingredient;
use Illuminate\Http\Request;

class IngredientController extends Controller
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
            'name' => 'required|string'
        ]);

        $data = $request->all();
        $ingredient_table = Ingredient::latest()->first();

        if ($ingredient_table) {
            $ingredient_id = $ingredient_table->id + 1;
        } else {
            $ingredient_id = 1;
        }
        
        $Ingredient = new Ingredient;
        $Ingredient->id = $ingredient_id;
        $Ingredient->name = $data['name'];

        if ($Ingredient->save()) {
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok',
                'data' => $Ingredient
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Failure to save ingredient'
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
        $Ingredient = Ingredient::where('id',$id)->get()[0];

        if ($Ingredient) {
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok',
                'data' => $Ingredient
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Ingredient not found'
            ];

            $this->status = 405;
        }

        return response()->json($this->responsedata,$this->status);
    }

    public function index()
    {
        $Ingredient = Ingredient::all();

        if ($Ingredient) {
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok',
                'data' => $Ingredient
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Ingredient not found'
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
        ]);

        $data = $request->all();
        
        $Ingredient = Ingredient::find($id);
        $Ingredient->name = $data['name'];
        
        if ($Ingredient->save()) {
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok',
                'data' => $Ingredient
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Failure to save ingredient'
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
        if (Ingredient::where('id',$id)->forceDelete()) {
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok'
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Ingredient not found'
            ];

            $this->status = 405;
        }

        return response()->json($this->responsedata,$this->status);
    }
}
