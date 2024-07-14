<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Stock;
use App\Models\Order;
use App\Models\User;
use DB;
use PDF;
use Response;
use Session;
use DataTables;
use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            return view('order.list');
        }catch (Exception $ex) {
            return redirect('/');
        }
    }

    public function create(Request $request)
    {
        try{
            $user = $request->user();
            $formData = $request->input('formData');
            foreach ($formData as $key=>$row) {
                $last_order_id = Order::whereMonth('created_at', date('m'))
                    ->whereYear('created_at', date('Y'))
                    ->orderby('id','desc')
                    ->first();
                $serial_no = 1;
                if($last_order_id){
                    $serial_no = $last_order_id->serial_no+1;
                }
                $row['serial_no']=$serial_no;
                $stock = Stock::where('design',$row['design'])->first()?$stock->id:1;
                $row['user_id'] = $user->id;
                $row['last_modified_user_id'] = $user->id;
                $row['stock_id'] = $stock;
                Order::create($row);
            }   
            return response()->json(['message' => 'Order Added successfully'], 200);
        }catch (Exception $ex) {
            return redirect('/');
        }
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getProductionOrders()
    {
        try{
            $orders = Order::where('is_active',1)->where('status',1)->orderBy('orders.id','ASC')->paginate(20); 
            return view('order.production_list',compact('orders'));
        }catch (Exception $ex) {
            return redirect('/');
        }
    }
    public function update(Request $request)
    {

        // Update orders
        foreach ($request->thickness as $index => $thickness) {
            // Find the order by ID
            $order = Order::findOrFail($request->id[$index]); // Assuming you have an 'id' field for each order
            
            // Update order details
            $order->thickness = $thickness;
            $order->length = $request->length[$index];
            $order->width = $request->width[$index];
            $order->quantity = $request->quantity[$index];
            $order->design = $request->design[$index];
            $order->frame = $request->frame[$index];
            $order->code = $request->code[$index];
            $order->remarks = $request->remarks[$index];

            // Save the changes
            $order->save();
        }

        // Return a success response
        return response()->json(['message' => 'Orders updated successfully']);
    }
    public function productionPrint(Request $request) {
        try{      
            $orders = Order::where('is_active',1)->where('status',1)->orderBy('orders.id','ASC')->get(); 
            if(!$orders->isEmpty()){
                $data=['orders'=>$orders];
                Order::where('status', 1)->update(['status' => 3]);
                ini_set('memory_limit', '512M');               
                $pdf = PDF::loadView('order/production_order_pdf',$data);
                $report = 'production_report_'.rand(10,100).'.pdf';
                $pdf->save(public_path('/reports/'.$report));
                $file= public_path('/reports/'.$report);
                    // $headers = array(
                    //         'Content-Type: application/pdf',
                    //         );
        
                return Response::download($file, 'production_list.pdf');
            }else{
                Session::flash('error', 'No Data to Print');
                return redirect(url('/'));
            }
            return view('order.list', array('orders' => $orders));
        }catch(Exception $e){
            return redirect(url('/'));
        }
    }
   public function getDispatchOrders()
   {
       try{
            $from_date = "null";
            $to_date = "null";
            $code = "";
            $orders = Order::where('is_active',1)->where('status',3)->orderBy('orders.id','ASC')->paginate(20); 
            return view('order.dispatch_list',compact('orders','from_date','to_date','code'));
       }catch (Exception $ex) {
           return redirect('/');
       }
   }

   public function dispatchSearch1(Request $request) {
    $from_date = $request->get('fromdate')?$request->get('fromdate'):'';
    $to_date = $request->get('todate')?$request->get('todate'):'';
    $code = $request->get('code')?$request->get('code'):'';
    $fromserial = $request->get('fromserial')?$request->get('fromserial'):'';
    $toserial = $request->get('toserial')?$request->get('toserial'):'';
    $orders = DB::table('orders')
    ->leftJoin('users', 'users.id', '=', 'orders.user_id')
    ->when($code == ""  && $from_date != "" && $to_date != "",function($query) use ($from_date,$to_date){
        $query->whereBetween('orders.created_at', [ date('Y-m-d', strtotime($from_date))." 00:00:00",  date('Y-m-d', strtotime($to_date))." 23:59:59"])->get();
    })
    ->when($code == "" && $from_date != "" && $to_date == "",function($query) use ($from_date,$to_date){
        $query->where('orders.created_at','>',date('Y-m-d', strtotime($from_date))." 00:00:00")->get();
    })
    ->when($code != "" && $from_date == "" && $to_date == "",function($query) use ($code,$from_date,$to_date){
        $query->where('code',$code)->get();
    })
    ->when($code != "" && $from_date != "" && $to_date == "",function($query) use ($code,$from_date,$to_date){
        $query->where('orders.created_at','>',date('Y-m-d', strtotime($from_date))." 00:00:00")
        ->where('code',$code)->get();
    })
    ->when($code != "" && $from_date != "" && $to_date != "",function($query) use ($code,$from_date,$to_date){
        $query->whereBetween('orders.created_at', [ date('Y-m-d', strtotime($from_date))." 00:00:00",  date('Y-m-d', strtotime($to_date))." 23:59:59"])
        ->where('code',$code)->get();
    })
    ->when($fromserial != "" && $toserial != "",function($query) use ($fromserial,$toserial){
        $query->whereBetween('serial_no',[intval($fromserial),intval($toserial)])->get();
    })
    ->when($fromserial != "" && $toserial == "",function($query) use ($fromserial,$toserial){
        $query->where('serial_no','>=',intval($fromserial))->get();
    })
    ->where('is_active',1)
    ->where('status',3)
    ->orderBy('orders.id','ASC')
    ->paginate(20);
    return view('order.dispatch_list', array('code'=>$code,'orders' => $orders,'from_date'=>$from_date,'to_date'=>$to_date,'fromserial'=>$fromserial,'toserial'=>$toserial));
}

public function dispatchSearch(Request $request) {
    $from_date = $request->get('fromdate') ?: '';
    $to_date = $request->get('todate') ?: '';
    $code = $request->get('code') ?: '';
    $fromserial = $request->get('fromserial') ?: '';
    $toserial = $request->get('toserial') ?: '';
    
    $query = DB::table('orders')
        ->leftJoin('users', 'users.id', '=', 'orders.user_id')
        ->where('is_active', 1)
        ->where('status', 3)
        ->orderBy('orders.id', 'ASC');

    if ($from_date && $to_date) {
        $query->whereBetween('orders.created_at', [date('Y-m-d', strtotime($from_date)) . " 00:00:00", date('Y-m-d', strtotime($to_date)) . " 23:59:59"]);
    } elseif ($from_date) {
        $query->where('orders.created_at', '>', date('Y-m-d', strtotime($from_date)) . " 00:00:00");
    }
    
    if ($code) {
        $query->where('code', $code);
    }
    
    if ($fromserial && $toserial) {
        $query->whereBetween('serial_no', [intval($fromserial), intval($toserial)]);
    } elseif ($fromserial) {
        $query->where('serial_no', '>=', intval($fromserial));
    }
    
    $orders = $query->paginate(20)->appends([
        'fromdate' => $from_date,
        'todate' => $to_date,
        'code' => $code,
        'fromserial' => $fromserial,
        'toserial' => $toserial
    ]);

    return view('order.dispatch_list', [
        'code' => $code,
        'orders' => $orders,
        'from_date' => $from_date,
        'to_date' => $to_date,
        'fromserial' => $fromserial,
        'toserial' => $toserial
    ]);
}


public function billingSearch(Request $request) {
    $from_date = $request->get('fromdate')?$request->get('fromdate'):'';
    $to_date = $request->get('todate')?$request->get('todate'):'';
    $code = $request->get('code')?$request->get('code'):'';
    $fromserial = $request->get('fromserial')?$request->get('fromserial'):'';
    $toserial = $request->get('toserial')?$request->get('toserial'):'';
    $orders = DB::table('orders')
    ->leftJoin('users', 'users.id', '=', 'orders.user_id')
    ->when($code == ""  && $from_date != "" && $to_date != "",function($query) use ($from_date,$to_date){
        $query->whereBetween('orders.created_at', [ date('Y-m-d', strtotime($from_date))." 00:00:00",  date('Y-m-d', strtotime($to_date))." 23:59:59"])->get();
    })
    ->when($code == "" && $from_date != "" && $to_date == "",function($query) use ($from_date,$to_date){
        $query->where('orders.created_at','>',date('Y-m-d', strtotime($from_date))." 00:00:00")->get();
    })
    ->when($code != "" && $from_date == "" && $to_date == "",function($query) use ($code,$from_date,$to_date){
        $query->where('code',$code)->get();
    })
    ->when($code != "" && $from_date != "" && $to_date == "",function($query) use ($code,$from_date,$to_date){
        $query->where('orders.created_at','>',date('Y-m-d', strtotime($from_date))." 00:00:00")
        ->where('code',$code)->get();
    })
    ->when($code != "" && $from_date != "" && $to_date != "",function($query) use ($code,$from_date,$to_date){
        $query->whereBetween('orders.created_at', [ date('Y-m-d', strtotime($from_date))." 00:00:00",  date('Y-m-d', strtotime($to_date))." 23:59:59"])
        ->where('code',$code)->get();
    })
    ->when($fromserial != "" && $toserial != "",function($query) use ($fromserial,$toserial){
        $query->whereBetween('serial_no',[intval($fromserial),intval($toserial)])->get();
    })
    ->when($fromserial != "" && $toserial == "",function($query) use ($fromserial,$toserial){
        $query->where('serial_no','>=',intval($fromserial))->get();
    })
    ->where('is_active',1)
    ->where('status',4)
    ->orderBy('orders.id','ASC')
    ->paginate(20);
        return view('order.billing_orders_list', array('code'=>$code,'orders' => $orders,'from_date'=>$from_date,'to_date'=>$to_date,'fromserial'=>$fromserial,'toserial'=>$toserial));
    }

    public function getDriverOrders(Request $request)
    {
        try{
            $request->session()->forget('previousData');
            $code = "";
            $driver = "";
            $data = Order::where('is_active',1)->where('status',3)->orderBy('orders.id','ASC')->get(); 
            return view('order.driver_orders_list',compact('data','code'));
        }catch (Exception $ex) {
            return redirect('/');
        }
    }

    public function getDriverOrdersSearch(Request $request) {
            $from_date = $request->get('fromdate')?$request->get('fromdate'):'';
            $to_date = $request->get('todate')?$request->get('todate'):'';
            $code = $request->get('code')?$request->get('code'):'';
            $fromserial = $request->get('fromserial')?$request->get('fromserial'):'';
            $toserial = $request->get('toserial')?$request->get('toserial'):'';
        if ($request->ajax()) {        
            $mergedData = [];
            $orders = DB::table('orders')
            ->leftJoin('users', 'users.id', '=', 'orders.user_id')
            ->when($code == ""  && $from_date != "" && $to_date != "",function($query) use ($from_date,$to_date){
                $query->whereBetween('orders.created_at', [ date('Y-m-d', strtotime($from_date))." 00:00:00",  date('Y-m-d', strtotime($to_date))." 23:59:59"])->get();
            })
            ->when($code == "" && $from_date != "" && $to_date == "",function($query) use ($from_date,$to_date){
                $query->where('orders.created_at','>',date('Y-m-d', strtotime($from_date))." 00:00:00")->get();
            })
            ->when($code != "" && $from_date == "" && $to_date == "",function($query) use ($code,$from_date,$to_date){
                $query->where('code',$code)->get();
            })
            ->when($code != "" && $from_date != "" && $to_date == "",function($query) use ($code,$from_date,$to_date){
                $query->where('orders.created_at','>',date('Y-m-d', strtotime($from_date))." 00:00:00")
                ->where('code',$code)->get();
            })
            ->when($code != "" && $from_date != "" && $to_date != "",function($query) use ($code,$from_date,$to_date){
                $query->whereBetween('orders.created_at', [ date('Y-m-d', strtotime($from_date))." 00:00:00",  date('Y-m-d', strtotime($to_date))." 23:59:59"])
                ->where('code',$code)->get();
            })
            ->when($fromserial != "" && $toserial != "",function($query) use ($fromserial,$toserial){
                $query->whereBetween('serial_no',[intval($fromserial),intval($toserial)])->get();
            })
            ->when($fromserial != "" && $toserial == "",function($query) use ($fromserial,$toserial){
                $query->where('serial_no','>=',intval($fromserial))->get();
            })
            ->where('is_active',1)
            ->where('status',3)
            ->orderBy('orders.id','ASC')
            ->get();
            if($code == ""  && $from_date == "" && $to_date == "" && $fromserial == "" && $toserial == ""){
                $orders = collect();
            }
            if ($orders->isNotEmpty()) {
                // $data = DB::table('orders')
                // ->where('code',$code)
                // ->where('is_active',1)
                // ->where('status',3)
                // ->orderBy('orders.id','ASC')
                // ->get();
                $previousData = $request->session()->get('previousData', []);
                $mergedData = array_merge($previousData, $orders->toArray());
                // Store the merged data in the session for future use
                $request->session()->put('previousData', $mergedData);
            }
            
            return DataTables::of($mergedData)->toJson();
            // return response()->json(['data' => $data]);
        }
        $request->session()->forget('previousData');
        return view('order.driver_orders_list', array('code'=>$code,'orders' => [],'from_date'=>$from_date,'to_date'=>$to_date,'fromserial'=>$fromserial,'toserial'=>$toserial));
    }

     public function driverPrint(Request $request) {
        try{ 
            $previousData = $request->session()->get('previousData', []);
            if(is_countable($previousData) && count($previousData)){
                $driverName = $request->input('driver_name');
                $data=['orders'=>$previousData,'driverName'=>$driverName];
                ini_set('memory_limit', '512M');               
                $pdf = PDF::loadView('order/driver_order_pdf',$data);
                $dispatch_report = 'dispatch_'.rand(10,100).'.pdf';
                $pdf->save(public_path('/reports/'.$dispatch_report));
                $file= public_path('/reports/'.$dispatch_report);


                $sumByCode = [];
                $quantity = 0;
                foreach ($previousData as $order) {
                    $code = $order->code; 
                    if(is_numeric($order->quantity)){ $quantity = $order->quantity; }

                    if (array_key_exists($code, $sumByCode)) {
                        $sumByCode[$code] += $quantity;
                    } else {
                        $sumByCode[$code] = $quantity;
                    }
                }
                $data=['orders'=>$sumByCode,'driverName'=>$driverName];    
                $pdf2 = PDF::loadView('order/gatepass_order_pdf',$data);
                $gatepass_report = 'gatepass_'.rand(10,100).'.pdf';
                $pdf2->save(public_path('/reports/'.$gatepass_report));
                $file2= public_path('/reports/'.$gatepass_report);

                foreach ($previousData as $order) {
                    $code = $order->code; 
                    Order::where('code',$code)->update(['status' => 4]);
                }

                return response()->json([
                    'pdf1' => $dispatch_report,
                    'pdf2' => $gatepass_report
                ]);
                    // $headers = array(
                    //         'Content-Type: application/pdf',
                    //         );
                // return response()->download($file);
                // return Response::download($file, 'order.pdf');
            }else{
                return response()->json(['msg'=>'success','response'=>'No Data']);
                // Session::flash('error', 'No Data to Print');
                // return redirect(url('/'));
            }
            return view('welcome', array('orders' => $orders,'from_date'=>$from_date,'to_date'=>$to_date,'fromserial'=>$fromserial,'toserial'=>$toserial,'selected_agent'=>$agent));
        }catch(Exception $e){
            return redirect(url('/'));
        }
    }

    public function getBilling(Request $request)
    {
        try{
            $orders = Order::
            where('is_active',1)->where('status',4)
            // ->where(
            //     'created_at', '>=', Carbon::now()->subMonth()->toDateTimeString()
            // )
            ->orderBy('orders.id','ASC')->get(); 
            return view('order.billing_orders_list',compact('orders'));
        }catch (Exception $ex) {
            return redirect('/');
        }
    }

}