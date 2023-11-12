<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Validator;
use App\Models\Stock;
use App\Models\Image;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Exports\OrderExport;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use Response;
use PDF;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try{
            $yesterday = date("Y-m-d", strtotime( '-1 days' ) );
            $loggedinrole = Auth::user()->role_id;
            $user = Auth::user();
            $page = ($request->has('pageindex')) ? (int) $request->input('pageindex') : 1;
            $limit = 10;
            $start = ($page == 1) ? 0 : ($page - 1) * $limit;
            $keyword = ($request->has('keyword')) ? $request->input('keyword') : '';

            $data = collect(Order::
             select('orders.*','users.fullname as username')
            ->leftJoin('users', 'users.id', '=', 'orders.user_id')
            ->when($keyword != "",function($query) use ($keyword){
                $query->where('orders.code', 'LIKE', $keyword.'%')->get();
            })
            ->where('orders.is_active',1)
            ->orderBy('orders.id','DESC')
            ->get())->skip($start)->take($limit)->toArray();

            if($loggedinrole == 2){
                $data = collect(Order::
                select('orders.*','users.fullname as username')
                ->leftJoin('users', 'users.id', '=', 'orders.user_id')
                ->when($keyword != "",function($query) use ($keyword){
                    $query->where('orders.code', 'LIKE', $keyword.'%')->get();
                })
                ->where('orders.is_active',1)
                ->where('orders.user_id',$user->id)
                ->orderBy('orders.id','DESC')
                ->get())->skip($start)->take($limit)->toArray();

            }elseif($loggedinrole == 3){
                $data = collect(Order::
                select('orders.*','users.fullname as username')
                ->leftJoin('users', 'users.id', '=', 'orders.user_id')
                ->when($keyword != "",function($query) use ($keyword){
                    $query->where('orders.code', 'LIKE', $keyword.'%')->get();
                })
                ->where('orders.is_active',1)
                ->where('orders.status',1)
                ->orderBy('orders.id','DESC')
                ->get())->skip($start)->take($limit)->toArray();

            }
            elseif($loggedinrole == 4){
                $data = collect(Order::
                select('orders.*','users.fullname as username')
                ->leftJoin('users', 'users.id', '=', 'orders.user_id')
                ->when($keyword != "",function($query) use ($keyword){
                    $query->where('orders.code', 'LIKE', $keyword.'%')->get();
                })
                ->where('orders.is_active',1)
                ->where('orders.status',2)
                ->orderBy('orders.id','DESC')
                ->get())->skip($start)->take($limit)->toArray();

            }
            elseif($loggedinrole == 5){
                $data = collect(Order::
                select('orders.*','users.fullname as username')
                ->leftJoin('users', 'users.id', '=', 'orders.user_id')
                ->when($keyword != "",function($query) use ($keyword){
                    $query->where('orders.code', 'LIKE', $keyword.'%')->get();
                })
                ->where('orders.is_active',1)
                ->where('orders.status',3)
                ->orderBy('orders.id','DESC')
                ->get())->skip($start)->take($limit)->toArray();

            }
            elseif($loggedinrole == 6){
                $data = collect(Order::
                select('orders.*','users.fullname as username')
                ->leftJoin('users', 'users.id', '=', 'orders.user_id')
                ->when($keyword != "",function($query) use ($keyword){
                    $query->where('orders.code', 'LIKE', $keyword.'%')->get();
                })
                ->where('orders.is_active',1)
                ->where('orders.status',4)
                ->orderBy('orders.id','DESC')
                ->get())->skip($start)->take($limit)->toArray();

            }
            $response = array(
                    'hasError' => false,
                    'errorCode' => -1,
                    'message' => 'Success',
                    'response' => array_values($data),
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
            $user = $request->user();
            // $this->authorize('create-edit-destroy-stock');
            $validator =Validator::make($request->all(), [
                'code'=>'required',
                'design'=>'required',
                'length'=>'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
                'width'=>'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
                'quantity'=>'required|numeric',
                // 'frame'=>'required',
                // 'remarks'=>'required',
                // 'thickness'=>'required',
                // 'status'=>'required',
            ]);
            $request['user_id']=$user->id;
            $request['last_modified_user_id']=$user->id;
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
                $design = $request->design;
                // $stocks = Stock::where('design','like', $design.'%')->get();
                 $stocks = Stock::where('design', $design)->get();

                //filter bu qty
                $stock_array = array();
                foreach($stocks as $key=>$stock){
                    if($stock->quantity>=($request->quantity)*2){
                        array_push($stock_array,$stock);
                    }
                }
                $stock_array_2 = array();
                if(count($stock_array) != 0){
                    foreach($stock_array as $key=>$stock){
                        if($stock->width>$request->width){
                            array_push($stock_array_2,$stock);
                        }
                    }  
                }else{
                    $response = array(
                        'hasError' => TRUE,
                        'errorCode' => 500,
                        'message' => 'quantity mismatch',
                        'response' => null
                    );
                    return \Response::json($response);

                }
                $result_stock = array();
                if(count($stock_array_2) != 0){
                    $widthset = array_column($stock_array_2, 'width');
                    $width =  min($widthset);
                    foreach($stock_array_2 as $key=>$stock){
                        if($stock->width == $width){
                            $result_stock = $stock;
                        }
                    }
                    $request['stock_id']=$result_stock->id;
                    $request['status']=1;
                    $request['design']=$result_stock->design;
                    $last_order_id = Order::whereMonth('created_at', date('m'))
                    ->whereYear('created_at', date('Y'))
                    ->orderby('id','desc')
                    ->first();
                    $serial_no = 1;
                    if($last_order_id){
                        $serial_no = $last_order_id->serial_no+1;
                    }
                    $request['serial_no']=$serial_no;

                    $data = Order::create($request->all());
                    $stock_lat = Stock::find($result_stock->id);
                    $update_stock = $stock_lat->update([
                        'quantity'=>($result_stock->quantity)-(($request->quantity)*2),
                    ]);
                    $response = array(
                        'hasError' => false,
                        'errorCode' => -1,
                        'message' => 'Order Created Successfully',
                        'response' => $data
                    );
                    // $result_stock = Stock::where('width',$width)->fir
                }else{
                    $response = array(
                        'hasError' => TRUE,
                        'errorCode' => 500,
                        'message' => 'width mismatch',
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

    public function uploadImage(Request $request)
    {
        // return $request;
        if(!$request->hasFile('image')) {
            $response = array(
                'hasError' => TRUE,
                'errorCode' => 400,
                'message' => 'upload_file_not_found',
                'response' => null
            );
            return \Response::json($response);
        }
    
        $allowedfileExtension=['pdf','jpg','png'];
        $files = $request->file('image'); 
        $errors = [];
        $imageArray = array();
        foreach ($files as $file) {  
            $extension = $file->getClientOriginalExtension();
            $check = in_array($extension,$allowedfileExtension);
            if($check) {
                foreach($request->image as $mediaFiles) {
                    $path = $mediaFiles->store('images');
                    $name = $mediaFiles->getClientOriginalName();
                    array_push($imageArray,$path);
                    //store image file into directory and db
                    // $save = new Image();
                    // $save->title = $name;
                    // $save->path = $path;
                    // $save->save();
                }
            } else {
                $response = array(
                    'hasError' => TRUE,
                    'errorCode' => 422,
                    'message' => 'invalid_file_format',
                    'response' => null
                );
                return \Response::json($response);
            }
            $response = array(
                'hasError' => false,
                'errorCode' => -1,
                'message' => 'File Uploaded Successfully',
                'response' => $imageArray
            );
            return \Response::json($response);
    
        }
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
            $user = $request->user();
            // $this->authorize('create-edit-destroy-stock');
            $validator =Validator::make($request->all(), [
                'code'=>'required',
                'design'=>'required',
                'length'=>'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
                'width'=>'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
                'quantity'=>'required|numeric',
                // 'frame'=>'required',
                // 'remarks'=>'required',
                // 'thickness'=>'required',
                // 'status'=>'required',
            ]);
            $request['last_modified_user_id']=$user->id;
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
                $old_order = Order::find($id);
                $old_stock = Stock::find($old_order->stock_id);
                $design = $request->design;
                if($old_order->design == $design){
                    if($old_order->width >= $request->width){
                        $total_quantity = (($old_order->quantity)*2)+$old_stock->quantity;
                        if(($request->quantity)*2<=($old_order->quantity)*2){
                            $stock_update = $old_stock->update(['quantity'=>($old_stock->quantity+(($old_order->quantity)*2-($request->quantity)*2))]);
                            $data = $old_order->update($request->all());
                            $response = array(
                                'hasError' => false,
                                'errorCode' => -1,
                                'message' => 'Order Updated Successfully',
                                'response' => $data
                            );
                            return \Response::json($response);
                        }elseif(($request->quantity)*2<=$total_quantity){
                            $stock_update = $old_stock->update(['quantity'=>$total_quantity-($request->quantity)*2]);
                            $data = $old_order->update($request->all());
                            $response = array(
                                'hasError' => false,
                                'errorCode' => -1,
                                'message' => 'Success',
                                'response' => $data
                            );
                            return \Response::json($response);

                        }
                    }
                }

                // $stocks = Stock::where('design','like', $design.'%')->get();
                $stocks = Stock::where('design', $design)->get();

                //filter bu qty
                $stock_array = array();
                foreach($stocks as $key=>$stock){
                    if($stock->quantity>=($request->quantity)*2){
                        array_push($stock_array,$stock);
                    }
                }
               
                $stock_array_2 = array();
                if(count($stock_array) != 0){
                    foreach($stock_array as $key=>$stock){
                        if($stock->width>$request->width){
                            array_push($stock_array_2,$stock);
                        }
                    }  
                }else{
                    $response = array(
                        'hasError' => TRUE,
                        'errorCode' => 500,
                        'message' => 'quantity mismatch',
                        'response' => null
                    );
                    return \Response::json($response);

                }
                $result_stock = array();
                if(count($stock_array_2) != 0){
                    $widthset = array_column($stock_array_2, 'width');
                    $width =  min($widthset);
                    foreach($stock_array_2 as $key=>$stock){
                        if($stock->width == $width){
                            $result_stock = $stock;
                        }
                    }
                    $old_order = Order::find($id);
                    $old_stock = Stock::find($old_order->stock_id);
                    $old_stock->update(['quantity'=>$old_stock->quantity+($old_order->quantity)*2]);
                    if($old_stock->id == $result_stock->id){
                        $result_stock=Stock::find($result_stock->id);
                    }
                    $request['design']=$result_stock->design;
                    $request['stock_id']=$result_stock->id;
                    $stock_lat = Stock::find($result_stock->id);
                    $update_stock = $stock_lat->update([
                        'quantity'=>($result_stock->quantity)-(($request->quantity)*2),
                    ]);
                    $data = Order::whereId($id)->update($request->all()); 
                    // if($request->status == 4){
                    //     $gate_pass = Order::whereId($id)->update(['driver_name'=>$request->driver_name,'route'=>$request->route]);
                    //
                //  }
                    $response = array(
                        'hasError' => false,
                        'errorCode' => -1,
                        'message' => 'Success',
                        'response' => $data
                    );
                    // $result_stock = Stock::where('width',$width)->fir
                }else{
                    $response = array(
                        'hasError' => TRUE,
                        'errorCode' => 500,
                        'message' => 'width mismatch',
                        'response' => null
                    );
                    return \Response::json($response);
                    
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $order = Order::find($id);
            if($order->status == 1){
                $check =  Order::whereId($id)->update(['is_active'=>0]);
                $order = Order::find($id);
                $stock = Stock::find($order->stock_id);
                $stock->update(['quantity'=>$stock->quantity+($order->quantity)*2]);
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
            }else{
                $response = array(
                    'hasError' => TRUE,
                    'errorCode' => 1,
                    'message' => "Access Denied",
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

    public function get_order_data(Request $request)
    {
        try{
            $from_date = $request->get('fromdate')?$request->get('fromdate'):'';
            $to_date = $request->get('todate')?$request->get('todate'):'';
            $orderList = collect(DB::table('orders')
            ->when($from_date != "" && $to_date != "",function($query) use ($from_date,$to_date){
                $query->whereBetween('created_at', [ date('Y-m-d', strtotime($from_date))." 00:00:00",  date('Y-m-d', strtotime($to_date))." 23:59:59"])->get();
            })
            ->when($from_date != "" && $to_date == "",function($query) use ($from_date,$to_date){
                $query->where('created_at','>',date('Y-m-d', strtotime($from_date))." 00:00:00")->get();
            })
            ->select('id','thickness','length','width','quantity','design','code','remarks','status')->get())->toArray();
            if($orderList){
                $data=['orderList'=>$orderList];
                $pdf = PDF::loadView('order_pdf',$data);
                $report = 'report_'.rand(10,100).'.pdf';
                $pdf->save(public_path('/reports/'.$report));
                
                $file= public_path('/reports/'.$report);
                $headers = array(
                        'Content-Type: application/pdf',
                        );
    
                return Response::download($file, 'filename.pdf', $headers);
                $response = array(
                    'hasError' => false,
                    'errorCode' => -1,
                    'message' => 'Success',
                    'response' => null,
                );
            }else{
                $response = array(
                    'hasError' => false,
                    'errorCode' => -1,
                    'message' => 'No data found',
                    'response' => null,
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
