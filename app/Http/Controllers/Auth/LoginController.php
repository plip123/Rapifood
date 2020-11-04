<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class LoginController extends Controller
{
    private $responsedata;
    private $status;


    public function __construct()
    {
        $this->responsedata = array();
        $this->status = 200;
    }

    public function login (Request $request)
    {
        $request->validate([
            'email'       => 'required|string|email',
            'password'    => 'required|string',
            'remember_me' => 'boolean',
        ]);

        $user = User::where('email',strtolower($request->get('email')))->firstOrFail();

        if ($user) {
            if (Hash::check($request->get('password'),$user->password)){
                if ($user->activate){
                    $token = Str::random(255);
                    $user->api_token = \hash('sha256',$token);
                    $user->save();
                    $this->responsedata = [
                        'email'=> $user->email,
                        'token'=> $user->api_token,
                        'status' => true
                    ];
                } else {
                    $this->responsedata = [
                        'error'=> ['Inactive user'],
                        'status' => false,
                        'message' => 'Inactive user'
                    ];
                    $this->status = 401;
                }
            } else {
                $this->responsedata = [
                    'error'=> ['Invalid credentials'],
                    'status' => false,
                    'message' => 'Invalid credentials'
                ];
                $this->status = 401;
            }
        } else {
            $this->responsedata = [
                'error'=> ['Invalid credentials'],
                'status' => false,
                'message' => 'Invalid credentials'
            ];
            $this->status = 401;
        }

        return response()->json($this->responsedata,$this->status);
    }

    public function logout()
    {
        $accestoken = Auth::user()->token();
        $accestoken->revoke();
        $accestoken->delete();

        $this->responsedata = [
            'status' => true,
            'message' => 'Successfully closed session'
        ];

        return response()->json(null,204);
    }
}
