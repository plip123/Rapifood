<?php

namespace App\Http\Controllers;

use App\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    private $responsedata;
    private $status;


    public function __construct()
    {
        $this->responsedata = array();
        $this->status = 200;
    }

    /**
     * Notification a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'userID' => 'required|integer',
            'message' => 'required|string',
            'state' => 'required|string'
        ]);

        $data = $request->all();
        $notification_table = Notification::latest()->first();

        if ($notification_table) {
            $notification_id = $notification_table->id + 1;
        } else {
            $notification_id = 1;
        }
        
        $Notification = new Notification;
        $Notification->id = $notification_id;
        $Notification->userID = $data['userID'];
        $Notification->message = $data['message'];
        $Notification->state = $data['state'];

        if ($Notification->save()) {
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok',
                'data' => $Notification
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Failure to save notification'
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
        $Notification = Notification::where('id',$id)->get()[0];

        if ($Notification) {
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok',
                'data' => $Notification
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Notification not found'
            ];

            $this->status = 405;
        }

        return response()->json($this->responsedata,$this->status);
    }


    public function index()
    {
        $Notification = Notification::all();

        if ($Notification) {
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok',
                'data' => $Notification
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Notification not found'
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
            'message' => 'required|string',
            'state' => 'required|string'
        ]);

        $data = $request->all();
        
        $Notification = Notification::find($id);
        $Notification->userID = $data['userID'];
        $Notification->message = $data['message'];
        $Notification->state = $data['state'];
        
        if ($Notification->save()) {
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok',
                'data' => $Notification
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Failure to save notification'
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
        if (Notification::where('id',$id)->forceDelete()) {
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok'
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Notification not found'
            ];

            $this->status = 405;
        }

        return response()->json($this->responsedata,$this->status);
    }
}
