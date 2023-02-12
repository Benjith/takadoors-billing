<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RawMaterial;
use App\Models\Stock;

use Illuminate\Support\Facades\Validator;

class RawMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $data = RawMaterial::where('status',1)->orderBy('id','Desc')->get();
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
        try{
            $validator =Validator::make($request->all(), [
                'name'=>'required',
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors();
                $response = array(
                    'hasError' => TRUE,
                    'errorCode' => 1,
                    'message' => $errors,
                    'response' => null
                );
                return \Response::json($response);
            }

            // Check if validation pass then create user and auth token. Return the auth token
            if ($validator->passes()) {
                $data = RawMaterial::create($request->all());
                $response = array(
                    'hasError' => false,
                    'errorCode' => -1,
                    'message' => 'Rawmaterial Created Successfully',
                    'response' => $data
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
            $product=RawMaterial::find($id);
            $data = $product->update($request->all());
            $response = array(
                'hasError' => false,
                'errorCode' => -1,
                'message' => 'Rawmaterial Updated Successfully',
                'response' => $data
            );
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
            $raw_stocks = Stock::where('material_id',$id)->update(['status'=>3]);
            $check =  RawMaterial::where('id',$id)->update(['status'=>0]);
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
