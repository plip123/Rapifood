<?php

namespace App\Http\Controllers;

use App\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoreController extends Controller
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
            'userID' => 'required|integer',
            'logo' => 'file',
            'address' => 'required|string',
            'city' => 'required|string'
        ]);

        $data = $request->all();
        $store_table = Store::latest()->first();

        if ($store_table) {
            $store_id = $store_table->id + 1;
        } else {
            $store_id = 1;
        }

        $path = Storage::putFile('public/stores', $request->file('logo'));

        $Store = new Store;
        $Store->id = $store_id;
        $Store->name = $data['name'];
        $Store->userID = $data['userID'];
        $Store->logo = $path;
        $Store->address = $data['address'];
        $Store->city = $data['city'];

        if ($Store->save()) {
            $Store->id = $store_id;
            $Store->logo = Storage::url($path);
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok',
                'data' => $Store,
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Failure to save store'
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
        $Store = Store::where('id',$id)->get();

        if ($Store) {
            $Store->logo = Storage::url($Store[0]->image);
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok',
                'data' => $Store[0]
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Store not found'
            ];

            $this->status = 405;
        }

        return response()->json($this->responsedata,$this->status);
    }


    public function index()
    {
        $Store = Store::all();

        if ($Store) {
            $responseStore = array();
            foreach ($Store as $store) {
                $store->logo = Storage::url($store->logo);
                array_push($responseStore, $store);
            }

            $this->responsedata = [
                'status' => true,
                'message' => 'Ok',
                'data' => $responseStore
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Store not found'
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
            'userID' => 'required|integer',
            'logo' => 'file',
            'address' => 'required|string',
            'city' => 'required|string'
        ]);

        $data = $request->all();
        
        $Store = Store::find($id);
        $Store->name = $data['name'];
        $Store->userID = $data['userID'];
        Storage::delete($Store->image);
        $path = Storage::putFile('public/stores', $request->file('logo'));
        $Store->logo = $path;
        $Store->address = $data['address'];
        $Store->city = $data['city'];

        if ($Store->save()) {
            $Store->logo = Storage::url($path);
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok',
                'data' => $Store
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Failure to save store'
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
        $Store = Store::where('id',$id)->get()[0];
        $path = $Store->image;

        if ($Store->forceDelete()) {
            Storage::delete($path);
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok'
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Store not found'
            ];

            $this->status = 405;
        }

        return response()->json($this->responsedata,$this->status);
    }
}
