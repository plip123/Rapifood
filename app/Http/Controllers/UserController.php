<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\User;

class UserController extends Controller
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
            'lastname' => 'required|string',
            'roleID' => 'required|integer',
            'address' => 'required|string',
            'avatar' => 'file',
            'city' => 'required|string',
        ]);

        $data = $request->all();
        $user_table = User::latest()->first();

        if ($user_table) {
            $user_id = $user_table->id + 1;
        } else {
            $user_id = 1;
        }

        $path = Storage::putFile('public/users', $request->file('avatar'));
        
        $User = new User;
        $User->id = $user_id;
        $User->name = $data['name'];
        $User->lastname = $data['lastname'];
        $User->roleID = $data['roleID'];
        $User->address = $data['address'];
        $User->avatar = $path;
        $User->city = $data['city'];

        if ($User->save()) {
            $User->id = $User_id;
            $User->avatar = Storage::url($path);
            $this->responsedata = array(
                'status' => true,
                'message' => 'Ok',
                'data' => array(
                    'name' => $User->name,
                    'lastname' => $User->lastname,
                    'roleID' => $User->roleID,
                    'adress' => $User->address,
                    'avatar' => $User->avatar,
                    'city' => $User->city,
                ),
            );
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Failure to save User'
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
        $User = User::where('id',$id)->get()[0]   ;

        if ($User) {
            $User->image = Storage::url($User->image);
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok',
                'data' => array(
                    'name' => $User->name,
                    'lastname' => $User->lastname,
                    'roleID' => $User->roleID,
                    'adress' => $User->address,
                    'avatar' => $User->avatar,
                    'city' => $User->city,
                )
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'User not found'
            ];

            $this->status = 405;
        }

        return response()->json($this->responsedata,$this->status);
    }


    public function index()
    {
        $User = User::all();

        if ($User) {
            $responseUser = array();
            foreach ($User as $user) {
                $User->image = Storage::url($user->image);
                array_push($responseUser, array(
                    'name' => $User->name,
                    'lastname' => $User->lastname,
                    'roleID' => $User->roleID,
                    'adress' => $User->address,
                    'avatar' => $User->avatar,
                    'city' => $User->city,
                ));
            }

            $this->responsedata = [
                'status' => true,
                'message' => 'Ok',
                'data' => $responseUser
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'User not found'
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
            'lastname' => 'required|string',
            'roleID' => 'required|integer',
            'address' => 'required|string',
            'avatar' => 'file',
            'city' => 'required|string',
        ]);

        $data = $request->all();
        
        $User = User::find($id);
        $User->name = $data['name'];
        $User->lastname = $data['lastname'];
        $User->roleID = $data['roleID'];
        $User->address = $data['address'];
        Storage::delete($User->image);
        $path = Storage::putFile('public/users', $request->file('image'));
        $User->avatar = $path;
        $User->city = $data['city'];

        if ($User->save()) {
            $User->image = Storage::url($path);
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok',
                'data' => array(
                    'name' => $User->name,
                    'lastname' => $User->lastname,
                    'roleID' => $User->roleID,
                    'adress' => $User->address,
                    'avatar' => $User->avatar,
                    'city' => $User->city,
                )
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'Failure to save User'
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
        $User = User::where('id',$id)->get()[0];
        $path = $User->image;

        if ($User->forceDelete()) {
            Storage::delete($path);
            $this->responsedata = [
                'status' => true,
                'message' => 'Ok'
            ];
        } else {
            $this->responsedata = [
                'error'=> ['Failed'],
                'status' => false,
                'message' => 'User not found'
            ];

            $this->status = 405;
        }

        return response()->json($this->responsedata,$this->status);
    }
}
