<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Extra;

class ExtraController extends Controller
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
            'image' => 'file',
            'price' => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/'
        ]);

        $data = $request->all();
        $extra_table = Extra::latest()->first();

        if ($extra_table) {
            $extra_id = $extra_table->id + 1;
        } else {
            $extra_id = 1;
        }

        $path = Storage::putFile('public/extras', $request->file('image'));
        
        $Extra = new Extra;
        $Extra->id = $extra_id;
        $Extra->name = $data['name'];
        $Extra->image = $path;
        $Extra->price = $data['price'];

        if ($Extra->save()) {
            $Extra->id = $extra_id;
            $Extra->image = Storage::url($path);
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok',
                'data' => $Extra
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Failure to save Extra'
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
        $Extra = Extra::where('id',$id)->get()[0];

        if ($Extra) {
            $Extra->image = Storage::url($Extra->image);
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok',
                'data' => $Extra
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Extra not found'
            ];

            $this->status = 405;
        }

        return response()->json($this->responsedata,$this->status);
    }

    public function index()
    {
        $Extra = Extra::all();

        if ($Extra) {
            $responseExtra = array();
            foreach ($Extra as $extra) {
                $Extra->image = Storage::url($extra->image);
                array_push($responseExtra, $extra);
            }

            $this->responsedata = [
                'status' => true,
                'message' => 'Ok',
                'data' => $responseExtra
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Extra not found'
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
            'image' => 'file',
            'price' => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/'
        ]);

        $data = $request->all();

        $Extra = Extra::find($id);
        $Extra->name = $data['name'];
        Storage::delete($Extra->image);
        $path = Storage::putFile('public/extras', $request->file('image'));
        $Extra->image = $path;
        $Extra->price = $data['price'];

        if ($Extra->save()) {
            $Extra->image = Storage::url($path);
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok',
                'data' => $Extra
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Failure to save Extra'
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
        $Extra = Extra::where('id',$id)->get()[0];
        $path = $Extra->image;

        if ($Extra->forceDelete()) {
            Storage::delete($path);
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok'
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Extra not found'
            ];

            $this->status = 405;
        }

        return response()->json($this->responsedata,$this->status);
    }
}
