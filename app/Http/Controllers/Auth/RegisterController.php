<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\User;


class RegisterController extends Controller
{
    private $responsedata;
    private $status;


    public function __construct()
    {
        $this->responsedata = array();
        $this->status = 200;
    }

    public function store (Request $request) {

        $request->validate([
            'name' => 'required|string',
            'lastname' => 'required|string',
            'email'    => 'required|string|email|unique:users',
            'password' => 'required|string',
        ]);

        $user = null;
        $token = Str::random(255);

        $user = new User([
            'name' => $request->get('name'),
            'lastname' => $request->get('lastname'),
            'email'    => strtolower($request->get('email')),
            'roleID' => 3,
            'address' => "",
            'city' => "",
            'password' => Hash::make($request->get('password'))
        ]);

        

        if($user) {
            $token = Str::random(255);
            $user->api_token = \hash('sha256',$token);
            
            $user->save();
            $data = $user;
            //$data["token"] = $user;
            $this->responsedata = [
                'status' => true,
                'message' => 'Registered user',
                'data' => $data,
            ];
        } else {
            $this->responsedata = [
                'error'=> ['user failed'],
                'status' => false,
                'message' => 'User registration error'
            ];
            $this->status = 405;
        }
        
        return response()->json($this->responsedata,$this->status);
    }
}
