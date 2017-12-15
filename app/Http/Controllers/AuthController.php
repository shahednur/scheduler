<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function store(Request $request){
        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');

        $user = [
            'name'=>$name,
            'email'=>$email,
            'password'=>$password,
            'signin'=>[
                'href'=>'api/v1/user/signin',
                'method'=>'POST',
                'params'=>'email,password'
            ]
        ];

        $response = [
            'msg'=>'User created',
            'user'=>$user
        ];

        return response()->json($response,Response::HTTP_CREATED);
    }
    public function signin(Request $request){
        $email = $request->input('email');
        $password = $request->input('password');
        return 'saved sign in data';
    }
}