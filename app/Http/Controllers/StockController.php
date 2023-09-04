<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Stock;
use App\Models\RawMaterial;
use Illuminate\Support\Facades\Gate;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try{
            $material_id = $request->get('material_id');
            if($material_id){
                $data = Stock::where('material_id',$material_id)->where('status',1)->orderBy('id','DESC')->get();
                $response = array(
                    'hasError' => false,
                    'errorCode' => -1,
                    'message' => 'Success',
                    'response' => $data
                );
            }else{
                $data = Stock::all();
                $response = array(
                    'hasError' => false,
                    'errorCode' => -1,
                    'message' => 'Success',
                    'response' => $data
                );
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

    public function search(Request $request)
    {
        try{
            $search = $request->get('search');
            if($search){
                $data = Stock::where('design', 'LIKE', "%{$search}%")->where('status',1)->orderBy('id','DESC')->get();
                $response = array(
                    'hasError' => false,
                    'errorCode' => -1,
                    'message' => 'Success',
                    'response' => $data
                );
            }else{
                $data = Stock::all();
                $response = array(
                    'hasError' => false,
                    'errorCode' => -1,
                    'message' => 'Success',
                    'response' => $data
                );
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
            // $this->authorize('create-edit-destroy-stock');
            $validator =Validator::make($request->all(), [
                'material_id'=>'required',
                'design'=>'required',
                'height'=>'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
                'width'=>'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
                'quantity'=>'required|numeric',
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors();
                $response = array(
                    'hasError' => TRUE,
                    'errorCode' => 1,
                    'message' => $errors,
                    'response' => null
                );
            }

            // Check if validation pass then create user and auth token. Return the auth token
            if ($validator->passes()) {
                $exist = RawMaterial::find($request->material_id);
                if($exist){
                    $data = Stock::create($request->all());
                    $response = array(
                        'hasError' => false,
                        'errorCode' => -1,
                        'message' => 'Stock Created Successfully',
                        'response' => $data
                    );
                }else{
                    $response = array(
                        'hasError' => TRUE,
                        'errorCode' => 500,
                        'message' => 'Raw material Invalid',
                        'response' => null
                    );
                }
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
            if (! Gate::allows('edit-destroy-stock', $request)) {
                $response = array(
                    'hasError' => TRUE,
                    'errorCode' => 403,
                    'message' => 'Unauthorized Action' ,
                    'response' => null
                );
                return \Response::json($response,403);
            }
            $validator =Validator::make($request->all(), [
                'material_id'=>'required',
                'design'=>'required',
                'height'=>'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
                'width'=>'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
                'quantity'=>'required|numeric',
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors();
                $response = array(
                    'hasError' => TRUE,
                    'errorCode' => 1,
                    'message' => $errors,
                    'response' => null
                );
            }

            // Check if validation pass then create user and auth token. Return the auth token
            if ($validator->passes()) {
                $exist = RawMaterial::find($request->material_id);
                if($exist){
                    $stock = Stock::find($id);
                    $data = $stock->update($request->all());

                    $response = array(
                        'hasError' => false,
                        'errorCode' => -1,
                        'message' => 'Stock Updated Successfully',
                        'response' => $data
                    );
                }else{
                    $response = array(
                        'hasError' => TRUE,
                        'errorCode' => 500,
                        'message' => 'Raw material Invalid',
                        'response' => null
                    );
                }
            }
        }catch (Exception $ex) {
            $response = array(
                'hasError' => TRUE,
                'errorCode' => 5,
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
    public function destroy(Request $request,$id)
    {
        try{
            if (! Gate::allows('edit-destroy-stock', $request)) {
                $response = array(
                    'hasError' => TRUE,
                    'errorCode' => 403,
                    'message' => 'Unauthorized Action' ,
                    'response' => null
                );
                return \Response::json($response,403);
            }
            $check =  Stock::destroy($id);
            if($check == 1){
                $response = array(
                    'hasError' => false,
                    'errorCode' => -1,
                    'message' => 'Success',
                    'response' => True
                );
            }else{
                $response = array(
                    'hasError' => TRUE,
                    'errorCode' => 1,
                    'message' => "Something Went Wrong.",
                    'response' => null
                );
            }
        }catch (Exception $ex) {
            $response = array(
                'hasError' => TRUE,
                'errorCode' => 5,
                'message' => 'Server Error.' . $ex->getMessage(),
                'response' => null
            );
        }
        return \Response::json($response);
    }
}
