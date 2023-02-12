<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $data = User::orderBy('id','DESC')->get();
            $response = array(
                'hasError' => false,
                'errorCode' => -1,
                'message' => 'Success',
                'response' => $data
            );
            return \Response::json($response);
        }catch (Exception $ex) {
            $response = array(
                'hasError' => TRUE,
                'errorCode' => 500,
                'message' => 'Server Error.' . $ex->getMessage(),
                'response' => null
            );
        }
        return \Response::json($response); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            // Validate request data
        $validator = Validator::make($request->all(), [
                'fullname' => 'required|string|max:255',
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
    
            if ($validator->passes()) {
                $user = User::whereId($id)->update([
                    'fullname' => $request->fullname,
                    'phone' => $request->phone,
                    'role_id' => $request->roleId,
                ]);
                $response = array(
                    'hasError' => false,
                    'errorCode' => -1,
                    'message' => 'User Updated Successfully',
                    'response' => True
                );
                return \Response::json($response);
        }
        }catch (Exception $ex) {
            $response = array(
                'hasError' => TRUE,
                'errorCode' => 500,
                'message' => 'Server Error.' . $ex->getMessage(),
                'response' => null
            );
        }
        return \Response::json($response); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $check =  User::destroy($id);
            if($check == 1){
                $response = array(
                    'hasError' => false,
                    'errorCode' => -1,
                    'message' => 'Success',
                    'response' => True
                );
                return \Response::json($response); 
            }else{
                $response = array(
                    'hasError' => TRUE,
                    'errorCode' => 1,
                    'message' => "Something Went Wrong.",
                    'response' => null
                );
                return \Response::json($response);
            }
        }catch (Exception $ex) {
            $response = array(
                'hasError' => TRUE,
                'errorCode' => 500,
                'message' => 'Server Error.' . $ex->getMessage(),
                'response' => null
            );
        }
        return \Response::json($response); 
    }
}
