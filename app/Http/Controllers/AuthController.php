<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        
        // Validate request data
        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|max:255',
            // 'lastname' => 'required|string|max:255',
            // 'email' => 'required|email|unique:users|max:255',
            'password' => 'required|min:4',
            'phone' => 'required|numeric|min:10|unique:users',
            'roleId'=>'required',
        ]);

        // Return errors if validation error occur.
        if ($validator->fails()) {
            $errors = $validator->errors();
            $response = array(
                'hasError' => TRUE,
                'errorCode' => 400,
                'message' => $errors,
                'response' => False
            );
            return \Response::json($response,400);
        }

        // Check if validation pass then create user and auth token. Return the auth token
        if ($validator->passes()) {
            $user = User::create([
                'fullname' => $request->fullname,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role_id' => $request->roleId,
            ]);
            $token = $user->createToken('auth_token')->plainTextToken;
            
            // $data = array(
            //     'access_token' => $token,
            //     'token_type' => 'Bearer',
            // );
            $response = array(
                'hasError' => false,
                'errorCode' => -1,
                'message' => 'User Created Successfully',
                'response' => True
            );
            return \Response::json($response);
        }
    }

    public function login(Request $request)
    {
        try{
            if (!Auth::attempt($request->only('phone', 'password'))) {
                $response = array(
                    'hasError' => TRUE,
                    'errorCode' => 401,
                    'message' => 'Invalid login details',
                    'response' => null
                );
                return \Response::json($response,401);
            }
            $user = User::where('phone', $request['phone'])->firstOrFail();
            $token = $user->createToken('auth_token')->plainTextToken;
            $auth = array(
                'access_token' => $token,
            );
            $data = array( 
                'user' => $user,
                'auth' => $auth,
            );
            $response = array(
                'hasError' => false,
                'errorCode' => -1,
                'message' => 'Success',
                'response' => $data
            );
            return \Response::json($response); 
        }
        catch (Exception $ex) {
            $response = array(
                'hasError' => TRUE,
                'errorCode' => 500,
                'message' => 'Server Error.' . $ex->getMessage(),
                'response' => null
            );
        }
        return \Response::json($response);     
    }

    public function me(Request $request)
    {
        return $request->user();
    }

    public function ChangePassword(Request $request)
    {
    try{
       $validator=Validator::make($request->all(),[
          'current_password'    =>'required',
          'new_password'        =>'required|min:4|max:30',
          'user_id'             =>'required'
        //   'confirm_password' =>'required|same:new_password'
       ]);
       if ($validator->fails()) {
          return response()->json([
             'hasError' => TRUE,
             'message'=>'validations fails',
             'response' =>$validator->errors()
          ],422);
       }
       $user=User::where('id',$request->user_id)->first();
    if($user){
       if (Hash::check($request->current_password,$user->password)) {
          $user->update([
             'password'=>Hash::make($request->new_password)
          ]);
 
 
          return response()->json([
             'hasError' => FALSE,
             'errorCode' => 200,
             'message'=>' password successfully updated',
             'response'=>NULL,
            ],200);
        }
        else
        {
           return response()->json([
             'hasError' => TRUE,
             'errorCode' => 422,
              'message'=>'old password does not match',
              'response' =>$validator->errors()
           ],422);
        }
    }
    else{
        return response()->json([
            'hasError' => TRUE,
            'errorCode' => 500,
             'message'=>'No user found',
             'response' =>$validator->errors()
          ],500);
    }
    }
        catch (Exception $ex) {
            $response = array(
                'hasError' => TRUE,
                'errorCode' => 500,
                'message' => 'Server Error.' . $ex->getMessage(),
                'response' => null
            );
            return \Response::json($response);  
        }
     } 
}