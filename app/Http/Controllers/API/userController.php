<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Auth;

class userController extends Controller
{
    /**User create function*/
    public function userCreate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => "required|string",
            'phone' => "required|numeric",
            'email' => "required|string|unique:users,email",
            'password' => "required|string"
        ]);

        if($validator->fails())
        {
            $result = array(['status' => false, 'message' => 'User Validation Failed', 'error_message' => $validator->errors()]);

            return response()->json($result, 400);
        }

        $user = User::create([
            'name'      => $request->name,
            'phone'     => $request->phone,
            'email'     => $request->email,
            'password'  => bcrypt($request->password)

        ]);

        if($user->id)
        {
            $result = array(['status' => true, 'message' => 'User Create Successfully', 'data' => $user]);
            $responseCode = 200;
        }else{
            $result = array(['status' => fase, 'message' => 'User Create Failed']);
            $responseCode = 400;
        }

        return response()->json($result, $responseCode);
    }

    /**User get function*/
    public function userGet()
    {
        try{
            $user = User::all();
            $result = array(['status' => true, 'message' => count($user) . 'User Found', 'data' => $user]);
            $responseCode = 200;

            return response()->json($result);
        }catch(Exception $e){
            if(!$user)
            {
                $result = array(['status' => false, 'message' =>  'User Not Found', 'error' => $e->getMessage]);
                $responseCode = 400;

                return response()->json($result, $responseCode);
            }
        }
    }

     /**User get details function*/
     public function userGetDetails($id)
     {
        $users = User::findOrFail($id);
        if(!$users)
        {
            $result = array(['status' => false, 'message' => 'User Not Found']);
        }
        $result = array(['status' => true, 'message' => 'User Found', 'data' => $users]);

        return response()->json($result, 200);
     }

     /**User update function*/
     public function userUpdate(Request $request, $id)
     {
        $validator  = Validator::make($request->all(), [
            'name'  => "required|string",
            'phone' => "required|string",
            'email' => "required|string|unique:users,email,".$id
        ]);

        if($validator->fails())
        {
            $result = array(['status' => false, 'message' => 'Validation failed', 'error_message' => $validator->errors()]);

            return response()->json($result, 400);
        }

        $user = User::findOrFail($id);

        $user->name     = $request->name;
        $user->phone    = $request->phone;
        $user->email    = $request->email;
        $result         = $user->save();

        $result = array(['status' => true, 'message' => 'Update Successfully', 'data' => $user]);

        return response()->json($result, 200);
     }

     /**User delete function*/
     public function userDelete($id)
     {
        $user = User::findOrFail($id);
        if(!$user)
        {
            return response()->json(['status' => false, 'message' => 'user Not Found'], 404);
        }

        $user->delete();
        $result = array(['status' => true, 'message' => 'User Delete Successfully', 'data' => $user]);
        return response()->json($result, 200);
     }

      /**User login function*/

      public function login(Request $request)
      {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        if($validator->fails())
        {
            return response()->json(['status'=> false, 'message' => 'Validation Failed', 'error_message'=>$validator->errors()]);
        }

        //$crediantials = $request->only("email", "password");
        $credentials = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];


        if(Auth::attempt($credentials))
        {
            $user = Auth::user();

            //$token = $user->createToken('akdlasd')->accessToken;
            $token = $user->createToken('your-token-name')->accessToken;


            return response()->json(['status'=> true, 'message' => 'Login Successfully', 'token'=>$token]);
        }

        return response()->json(['status'=> false, 'message' => 'Login Failed']);
      }

      /**Logout Function*/
      public function logout()
        {
            $user = Auth::user();

            // Revoke all tokens associated with the user
            $user->tokens()->delete();

            return response()->json(['status' => true, 'message' => 'Logout Successfully', 'data' => $user]);
        }

}
