<?php

namespace App\Http\Controllers;

use App\Http\Resources\PrivateUserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.verify', ['except' => ['registerUser']]);
    }
    //
    public function registerUser(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'firstName' => 'required|string|min:3|max:50',
            'lastName' => 'required|string|min:3|max:50',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'verify_password' => 'required|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        //confirm if pssword is equal to veirfy_password
        if ($request->verify_password != $request->password) {
            return response()->json(['error' => 'Password missmatched']);
        }



        $user = User::create($validator->validated());

        return response()->json([
            'message' => "User Successfully added",
            'user' => new PrivateUserResource($user)
        ]);
    }
}
