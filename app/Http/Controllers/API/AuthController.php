<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;



class AuthController extends Controller
{

    // public function indexAction() {
    //     $user = User::all();
    //     return response()->json(array('status' => 200, 'users' => $user));
    // }

    /**
     * 
     */
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'FirstName' => 'required',
            'LastName' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        try {

            $user = new User();
            $user->FirstName = $request->FirstName;
            $user->LastName = $request->LastName;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->AccessType = "User";

            $user->save();

            return response()
                ->json([
                    'Message' => 'Successfully created a new account!',
                    'User' => $user
                ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return response()
                ->json([
                    "Message" => $e->getMessage()
                ]);
        }
    }

    /**
     * 
     */

    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (Auth::guard('web')->attempt($credentials)) {
            $user = Auth::guard('web')->user();
            $user->api_token = Str::random(60);

            /** @var \App\Models\User $user **/
            $user->save();

            return response()
                ->json([
                    'Message' => 'Successfully logged in!',
                    'User' => $user->toArray(),
                ]);
        }
        return response()->json(['Message' => 'Something went wrong'], 401);
    }

    public function logout(Request $request)
    {
        $user = Auth::guard('api')->user();
        $user->api_token = null;

        /** @var \App\Models\User $user **/
        $user->save();

        return response()
            ->json([
                "Status" => 200,
                'User' => $user
            ], 200);
    }

    public function index()
    {
        $users = User::all();
        return response()->json(['status' => '200', 'users' => $users]);
    }

}