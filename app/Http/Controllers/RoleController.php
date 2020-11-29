<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
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
            'permission_lvl' => 'required|integer'
        ]);

        $data = $request->all();
        $role_table = Role::latest()->first();

        if ($role_table) {
            $role_id = $role_table->id + 1;
        } else {
            $role_id = 1;
        }
        
        $Role = new Role;
        $Role->id = $role_id;
        $Role->name = $data['name'];
        $Role->permission_lvl = $data['permission_lvl'];

        if ($Role->save()) {
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok',
                'data' => $Role
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Failure to save role'
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
        $Role = Role::where('id',$id)->get()[0];

        if ($Role) {
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok',
                'data' => $Role
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Role not found'
            ];

            $this->status = 405;
        }

        return response()->json($this->responsedata,$this->status);
    }


    public function index()
    {
        $Role = Role::all();

        if ($Role) {
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok',
                'data' => $Role
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Role not found'
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
            'permission_lvl' => 'required|integer'
        ]);

        $data = $request->all();
        
        $Role = Role::find($id);
        $Role->name = $data['name'];
        $Role->permission_lvl = $data['permission_lvl'];
        
        if ($Role->save()) {
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok',
                'data' => $Role
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Failure to save role'
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
        if (Role::where('id',$id)->forceDelete()) {
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok'
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Role not found'
            ];

            $this->status = 405;
        }

        return response()->json($this->responsedata,$this->status);
    }
}
