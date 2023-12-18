<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api', ['except' => ['login',"register"]]);
    }
    
   
    public function login(Request $request)
    {
        $message=[
            'email.required' => 'Email is required',
        ];
       $validator= Validator::make($request->all(), [
           'email' => 'required|email',
           'password' => 'required|string|min:6',
       ],$message);
      
     if($validator->fails() ){
        return response()->json([
            $validator->errors(),422
        ]);
     }
     if(! $token = auth()->attempt($validator->validated())){
        return response()->json(['error' => 'Unauthorized'], 401);
     }
     return $this->createNewToken($token);
    }
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));
        $user->assignRole('editor');
        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }
    public function profile()
{
    $user = auth()->user();
    
    // Retrieve the roles associated with the user
    $roles = $user->getRoleNames();
    return response()->json([
        'user' => $user,
        'roles' => $roles,
    ]);
}
    public function logout(){
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }
    public function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            "expires_in" => Auth::factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }
}
