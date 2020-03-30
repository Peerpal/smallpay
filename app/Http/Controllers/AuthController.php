<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\User;

class AuthController extends Controller
{

    public $loginAfterSignUp = true;

    public function register(Request $request)
    {
      $user = User::create([
        'firstname' => $request->firstname,
        'lastname' => $request->lastname,
        'email' => $request->email,
        'phone' => $request->phone,
        'password' => bcrypt($request->password),
      ]);

      $token = auth()->login($user);
      return response()->json([
        'access_token' => $token,
        'token_type' => 'bearer'
      ]);
    //   return $this->respondWithToken($token);
    }

    public function login(Request $request)
    {
      $credentials = $request->only(['email', 'password']);

      if (!$token = auth()->attempt($credentials)) {
        return response()->json(['error' => 'Email and Password are not correct'], 401);
      }

      return response()->json([
        'access_token' => $token,
        'token_type' => 'bearer'
      ]);
    }
    public function getAuthUser(Request $request)
    {
        return response()->json(auth()->user());
    }
    public function logout()
    {
        auth()->logout();
        return response()->json(['message'=>'Successfully logged out']);
    }
    protected function respondWithToken($token)
    {
      return response()->json([
        'access_token' => $token,
        'token_type' => 'bearer',
        'expires_in' => auth('api')->factory()->getTTL() * 60
      ]);
    }

}
