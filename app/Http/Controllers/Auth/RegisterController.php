<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
            'user' => 'required|string',
            'name' => 'required|string',
            'lastname' => 'required|string',
            'email'    => 'required|string|email|unique:users',
            'roleID' => 'required|integer',
            'address' => 'required|string',
            'city' => 'required|string',
            'password' => 'required|string|confirmed',
        ]);

        $user = null;

        $user = new User([
            'user' => $request->get('user'),
            'name' => $request->get('name'),
            'lastname' => $request->get('lastname'),
            'email'    => strtolower($request->get('email')),
            'roleID' => $request->get('roleID'),
            'address' => $request->get('address'),
            'city' => $request->get('city'),
            'password' => Hash::make($request->get('password')),
        ]);

        

        if($user) {
            $user->save();

            $this->responsedata = [
                'status' => true,
                'message' => 'Registered user'
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
