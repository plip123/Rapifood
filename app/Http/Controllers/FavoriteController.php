<?php

namespace App\Http\Controllers;

use App\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    private $responsedata;
    private $status;


    public function __construct()
    {
        $this->responsedata = array();
        $this->status = 200;
    }

    /**
     * Favorite a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'userID' => 'required|integer',
            'productID' => 'required|integer',
            'status' => 'required|string'
        ]);

        $data = $request->all();
        $favorites_table = Favorite::latest()->first();

        if ($favorites_table) {
            $favorites_id = $favorites_table->id + 1;
        } else {
            $favorites_id = 1;
        }
        
        $Favorite = new Favorite;
        $Favorite->id = $favorites_id;
        $Favorite->userID = $data['userID'];
        $Favorite->productID = $data['productID'];
        $Favorite->status = $data['status'];

        if ($Favorite->save()) {
            $Favorite->id = $favorites_id;
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok',
                'data' => $Favorite
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Failure to save favorite'
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
        $Favorite = Favorite::where('id',$id)->get()[0];

        if ($Favorite) {
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok',
                'data' => $Favorite
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Favorite not found'
            ];

            $this->status = 405;
        }

        return response()->json($this->responsedata,$this->status);
    }


    public function index()
    {
        $Favorite = Favorite::all();

        if ($Favorite) {
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok',
                'data' => $Favorite
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Favorite not found'
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
            'userID' => 'required|integer',
            'productID' => 'required|integer',
            'status' => 'required|string'
        ]);

        $data = $request->all();
        
        $Favorite = Favorite::find($id);
        $Favorite->userID = $data['userID'];
        $Favorite->productID = $data['productID'];
        $Favorite->status = $data['status'];
        
        if ($Favorite->save()) {
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok',
                'data' => $Favorite
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Failure to save favorite'
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
        if (Favorite::where('id',$id)->forceDelete()) {
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok'
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Favorite not found'
            ];

            $this->status = 405;
        }

        return response()->json($this->responsedata,$this->status);
    }
}
