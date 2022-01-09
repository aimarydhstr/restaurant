<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
    	$validator = Validator::make($request->all(), [
    		'firstname' => 'required|string|max:255',
    		'lastname' => 'required|string|max:255',
    		'email' => 'required|string|email|max:255|unique:users',
    		'password' => 'required|string|confirmed|min:8',
    		'image' => 'string'
    	]);

    	if($validator->fails()){
    		return response()->json([
                'status' => false,
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ], 422);
    	}

        $image = $request->image;

        if($image == NULL){
            $image = 'noimage.png';
        }

    	$user = User::create([
    		'firstname' => $request->firstname,
    		'lastname' => $request->lastname,
    		'email' => $request->email,
    		'password' => \Hash::make($request->password),
    		'image' => $image,
    		'admin' => 0
    	]);

    	return response()->json([
    		'message' => 'Account created',
    		'data' => $user
    	], 201);
    }

    public function login(Request $request)
    {
    	if(!Auth::attempt($request->only('email', 'password'))){
    		return response()->json([
    			'message' => 'Unauthorized'
    		], 401);
    	}

    	$user = User::where('email', $request['email'])->firstOrFail();
    	$token = $user->createToken('auth_token')->plainTextToken;

    	return response()->json([
    		'message' => 'Hi '.$user->firstname,
    		'access_token' => $token,
    		'token_type' => 'Bearer'
    	], 200);
    }

    public function changePassword(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'oldpassword' => 'required|string|min:8',
            'newpassword' => 'required|string|min:8|confirmed',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::findOrFail($id);
        $up = $user->update([
            'password' => \Hash::make($request->newpassword)
        ]);

        return response()->json([
            'message' => 'Change Password Successfully',
            'data' => $user
        ], 201);
    }

    public function logout()
    {
    	auth()->user()->tokens()->delete();

    	return response()->json([
    		'message' => 'Logout successfully'
    	], 200);
    }
}
