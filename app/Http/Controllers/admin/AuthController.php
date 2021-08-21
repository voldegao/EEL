<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {

        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Unauthorized'
            ]);
        }

        $user = User::where('email', $request->email)->first();
        if ( ! Hash::check($request->password, $user->password, [])) {
            throw new \Exception('Error in Login');
        }

        // $user->tokens()->delete();

        $tokenResult = $user->createToken('auth')->plainTextToken;
        return response()->json([
            'status_code' => 200,
            'access_token' => $tokenResult,
            'token_type' => 'Bearer',
        ]);
    }

    public function register(Request $request)
    {
        $credentials = $request->all();

        $credentials['email'] = $credentials['email'];
        $credentials['password'] = hash::make($credentials['password']);

        $user = User::create($credentials);

        return response()->json($user);
    }

    public function verification(){
        try {

            if(Auth::user()->active==1){
                return 1;
            }else{
                return 0;
            }

        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
