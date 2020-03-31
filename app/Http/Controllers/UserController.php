<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    public function index()
    {
        $data["count"] = User::count();
        $user = array();

        foreach (User::all() as $p) {
            $item = [
                "id"        => $p->id,
                "Firstname" => $p->Firstname,
                "Lastname"  => $p->Lastname,
                "email"     => $p->email,
                "created_at" => $p->created_at,
                "updated_at" => $p->updated_at
            ];
        
        array_push($user, $item);
    }
    $data["user"] = $user;
    $data["status"] = 1;
    return response($data);
}
    public function login(Request $request){
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'logged' => false,
                    'message' => 'Invalid email and password' 
                ]);
            }
        } catch (JWTException $e) {
            return response()->json([
                'logged' => false,
                'message' => 'Generate Token Failed'
            ]);
        }
        return response()->json([
                "logged"    => true,
                "token"     => $token,
                "message"   => 'Login berhasil'
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Firstname'       => 'required|string|max:255',
            'Lastname'        => 'required|string|max:255',
            'email'           => 'required|string|email|max:100|unique:users',
            'password'        => 'required|string|min:8',
            'password_verify' => 'required|string|min:8',
        ]);

        if($validator->fails()){
            return response()->json([
                'status'    => 0,
                'message'   => $validator->errors()
            ]); 
        }

        $user = new User();
        $user->Firstname     = $request->Firstname;
        $user->Lastname      = $request->Lastname;
        $user->email         = $request->email;
        $user->password      = Hash::make($request->password);
        $user->password_verify = $request->password;
        $user->save();

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'status'    => '1',
            'message'   => 'User berhasil ditambahkan'

        ], 201); 
    }

    public function LoginCheck() {
        try {
            if(!$user = JWTAuth::parseToken()->authenticate()){
                return response()->json([
                    'auth'      => false,
                    'message'   => 'Invalid token'
                ]);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e){
            return response()->json([
                    'auth'      => false,
                    'message'   => 'Token expired'
            ], $e->getStatusCode());
        }  catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e){
            return response()->json([
                    'auth'      => false,
                    'message'   => 'Invalid token'
            ], $e->getStatusCode());
        }  catch (Tymon\JWTAuth\Exceptions\JWTException $e){
            return response()->json([
                    'auth'      => false,
                    'message'   => 'Token absent'
            ], $e->getStatusCode());
        } 
        return response()->json([
            "auth"      => true,
            "user"      => $user
        ], 201);
    }

    public function logout(Request $request)
    {
        if(JWTAuth::invalidate(JWTAuth::getToken())) {
            return response()->json([
                "logged"  => false,
                "message" => 'Logout berhasil'
            ], 201);
        } else {
            return response()->json([
                "logged"    => true,
                "message"   => 'Logout gagal'
            ], 201);
        }
    }
}
    
