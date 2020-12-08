<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Payment;

class PaymentController extends Controller
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
            'description' => 'string',
            'apiKey' => 'required|string',
            'url' => 'required|string'
        ]);

        $data = $request->all();
        $payment_table = Payment::latest()->first();
        $active = 0;

        if ($payment_table) {
            $payment_id = $payment_table->id + 1;
        } else {
            $payment_id = 1;
            $active = 1;
        }
        
        $Payment = new Payment;
        $Payment->id = $payment_id;
        $Payment->name = $data['name'];
        $Payment->description = $data['description'];
        $Payment->apiKey = $data['apiKey'];
        $Payment->url = $data['url'];
        $Payment->active = $active;

        if ($Payment->save()) {
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok',
                'data' => $Payment
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Failure to save payment'
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
        $Payment = Payment::where('id',$id)->get()[0];

        if ($Payment) {
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok',
                'data' => $Payment
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Payment not found'
            ];

            $this->status = 405;
        }

        return response()->json($this->responsedata,$this->status);
    }


    public function index()
    {
        $Payment = Payment::all();

        if ($Payment) {
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok',
                'data' => $Payment
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Payment not found'
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
            'apiKey' => 'required|string',
            'url' => 'required|string',
            'active' => 'required|integer'
        ]);

        $data = $request->all();
        
        $Payment = Payment::find($id);
        $Payment->name = $data['name'];
        $Payment->description = $data['description'];
        $Payment->apiKey = $data['apiKey'];
        $Payment->url = $data['url'];
        $Active = Payment::where('active',$data['active'])->get()[0];
        if ($Active) {
            $Active->active = 0;
            if ($Active->save()) {
                $Payment->active = $data['active'];
            }
        }

        if ($Payment->save()) {
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok',
                'data' => $Payment
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Failure to save payment'
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
        if (Payment::where('id',$id)->forceDelete()) {
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok'
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Payment not found'
            ];

            $this->status = 405;
        }

        return response()->json($this->responsedata,$this->status);
    }
}
