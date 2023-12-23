<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Stock;
use App\Models\Order;
use App\Models\User;
use DB;
use PDF;
use Response;
use Session;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $orders = Order::where('is_active',1)->orderBy('orders.id','ASC')->paginate(20); 
            // print_r(Session::all());exit();  
            $from_date = "null";
            $to_date = "null";
            $fromserial = ""; 
            $toserial = "";     
            $agents = User::all();
            $selected_agent = "";
            $code = "";
            return view('welcome',compact('code','selected_agent','orders','from_date','to_date','fromserial','toserial','agents'));
        }catch (Exception $ex) {
            return redirect('/');
        }
    }

    public function search(Request $request) {
        $from_date = $request->get('fromdate')?$request->get('fromdate'):'';
        $to_date = $request->get('todate')?$request->get('todate'):'';
        $fromserial = $request->get('fromserial')?$request->get('fromserial'):'';
        $toserial = $request->get('toserial')?$request->get('toserial'):'';
        $agent = $request->get('agent')?$request->get('agent'):'';
        $code = $request->get('code')?$request->get('code'):'';
        $orders = DB::table('orders')
        ->leftJoin('users', 'users.id', '=', 'orders.user_id')
        ->when($agent == "" && $code == ""  && $from_date != "" && $to_date != "",function($query) use ($agent,$from_date,$to_date){
            $query->whereBetween('orders.created_at', [ date('Y-m-d', strtotime($from_date))." 00:00:00",  date('Y-m-d', strtotime($to_date))." 23:59:59"])->get();
        })
        ->when($agent == "" && $code == "" && $from_date != "" && $to_date == "",function($query) use ($agent,$from_date,$to_date){
            $query->where('orders.created_at','>',date('Y-m-d', strtotime($from_date))." 00:00:00")->get();
        })
        ->when($agent != "" && $code == "" && $from_date != "" && $to_date != "",function($query) use ($agent,$from_date,$to_date){
            $query->whereBetween('orders.created_at', [ date('Y-m-d', strtotime($from_date))." 00:00:00",  date('Y-m-d', strtotime($to_date))." 23:59:59"])
            ->where('user_id',$agent)->get();
        })
        ->when($agent != ""  && $code == "" && $from_date != "" && $to_date == "",function($query) use ($agent,$from_date,$to_date){
            $query->where('orders.created_at','>',date('Y-m-d', strtotime($from_date))." 00:00:00")
            ->where('user_id',$agent)->get();
        })
        ->when($agent != ""  && $code == "" && $from_date == "" && $to_date == "",function($query) use ($agent,$from_date,$to_date){
            $query->where('user_id',$agent)->get();
        })
        ->when($agent == ""  && $code != "" && $from_date == "" && $to_date == "",function($query) use ($code,$from_date,$to_date){
            $query->where('code',$code)->get();
        })
        ->when($agent == ""  && $code != "" && $from_date != "" && $to_date == "",function($query) use ($code,$from_date,$to_date){
            $query->where('orders.created_at','>',date('Y-m-d', strtotime($from_date))." 00:00:00")
            ->where('code',$code)->get();
        })
        ->when($agent == ""  && $code != "" && $from_date != "" && $to_date != "",function($query) use ($code,$from_date,$to_date){
            $query->whereBetween('orders.created_at', [ date('Y-m-d', strtotime($from_date))." 00:00:00",  date('Y-m-d', strtotime($to_date))." 23:59:59"])
            ->where('code',$code)->get();
        })
        ->when($agent != ""  && $code != "" && $from_date == "" && $to_date == "",function($query) use ($code,$agent,$from_date,$to_date){
            $query->where('code',$code)
            ->where('user_id',$agent)->get();
        })
        ->when($agent != ""  && $code != "" && $from_date != "" && $to_date == "",function($query) use ($code,$agent,$from_date,$to_date){
            $query->where('code',$code)
            ->where('user_id',$agent)
            ->where('orders.created_at','>',date('Y-m-d', strtotime($from_date))." 00:00:00")->get();
        })
        ->when($agent != ""  && $code != "" && $from_date != "" && $to_date != "",function($query) use ($code,$agent,$from_date,$to_date){
            $query->where('code',$code)
            ->where('user_id',$agent)
            ->whereBetween('orders.created_at', [ date('Y-m-d', strtotime($from_date))." 00:00:00",  date('Y-m-d', strtotime($to_date))." 23:59:59"])
            ->get();
        })
        ->when($fromserial != "" && $toserial != "",function($query) use ($fromserial,$toserial){
            $query->whereBetween('serial_no',[intval($fromserial),intval($toserial)])->get();
        })
        ->when($fromserial != "" && $toserial == "",function($query) use ($fromserial,$toserial){
            $query->where('serial_no','>=',intval($fromserial))->get();
        })
        ->where('is_active',1)
        ->orderBy('orders.id','ASC')
        ->select('orders.id','thickness','length','users.fullname as username','width','quantity','design','code','remarks','orders.status','user_id','serial_no')->paginate(5);
        // foreach($orders as $key=>$value) {
        //   Session::put([$key=>$value]);
        // }
        $agents = User::all();
         return response()->json(['msg'=>'success','response'=>$orders]);
        //  return view('welcome', array('agents'=>$agents,'code'=>$code ,'selected_agent'=>$agent,'orders' => $orders,'from_date'=>$from_date,'to_date'=>$to_date,'fromserial'=>$fromserial,'toserial'=>$toserial));
    }
    public function print(Request $request) {
        try{  
            if(is_countable($request->orderList) && count($request->orderList)){
                $data=['orders'=>$request->orderList,'from_date'=>$request->fromdate,'to_date'=>$request->todate,'fromserial'=>$request->fromserial,'toserial'=>$request->toserial];
                ini_set('memory_limit', '512M');               
                $pdf = PDF::loadView('order_pdf',$data);
                $report = 'report_'.rand(10,100).'.pdf';
                $pdf->save(public_path('/reports/'.$report));
                $file= public_path('/reports/'.$report);
                    // $headers = array(
                    //         'Content-Type: application/pdf',
                    //         );
                return response()->download($file);
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
    // public function print(Request $request,$from_date,$to_date,$fromserial,$toserial,$agent,$code) {
    //     try{  

    //         if($from_date == "null"){
    //             $from_date = '';
    //         }
    //         if($to_date == "null"){
    //             $to_date = '';
    //         }
    //         if($fromserial == "null"){
    //             $fromserial = '';
    //         }
    //         if($toserial == "null"){
    //             $toserial = '';
    //         }
    //         if($agent == "null"){
    //             $agent = '';
    //         }
    //         if($code == "null"){
    //             $code = '';
    //         }
    //         $orders = DB::table('orders')
    //         ->when($agent == "" && $code == ""  && $from_date != "" && $to_date != "",function($query) use ($agent,$from_date,$to_date){
    //             $query->whereBetween('created_at', [ date('Y-m-d', strtotime($from_date))." 00:00:00",  date('Y-m-d', strtotime($to_date))." 23:59:59"])->get();
    //         })
    //         ->when($agent == "" && $code == "" && $from_date != "" && $to_date == "",function($query) use ($agent,$from_date,$to_date){
    //             $query->where('created_at','>',date('Y-m-d', strtotime($from_date))." 00:00:00")->get();
    //         })
    //         ->when($agent != "" && $code == "" && $from_date != "" && $to_date != "",function($query) use ($agent,$from_date,$to_date){
    //             $query->whereBetween('created_at', [ date('Y-m-d', strtotime($from_date))." 00:00:00",  date('Y-m-d', strtotime($to_date))." 23:59:59"])
    //             ->where('user_id',$agent)->get();
    //         })
    //         ->when($agent != ""  && $code == "" && $from_date != "" && $to_date == "",function($query) use ($agent,$from_date,$to_date){
    //             $query->where('created_at','>',date('Y-m-d', strtotime($from_date))." 00:00:00")
    //             ->where('user_id',$agent)->get();
    //         })
    //         ->when($agent != ""  && $code == "" && $from_date == "" && $to_date == "",function($query) use ($agent,$from_date,$to_date){
    //             $query->where('user_id',$agent)->get();
    //         })
    //         ->when($agent == ""  && $code != "" && $from_date == "" && $to_date == "",function($query) use ($code,$from_date,$to_date){
    //             $query->where('code',$code)->get();
    //         })
    //         ->when($agent == ""  && $code != "" && $from_date != "" && $to_date == "",function($query) use ($code,$from_date,$to_date){
    //             $query->where('created_at','>',date('Y-m-d', strtotime($from_date))." 00:00:00")
    //             ->where('code',$code)->get();
    //         })
    //         ->when($agent == ""  && $code != "" && $from_date != "" && $to_date != "",function($query) use ($code,$from_date,$to_date){
    //             $query->whereBetween('created_at', [ date('Y-m-d', strtotime($from_date))." 00:00:00",  date('Y-m-d', strtotime($to_date))." 23:59:59"])
    //             ->where('code',$code)->get();
    //         })
    //         ->when($agent != ""  && $code != "" && $from_date == "" && $to_date == "",function($query) use ($code,$agent,$from_date,$to_date){
    //             $query->where('code',$code)
    //             ->where('user_id',$agent)->get();
    //         })
    //         ->when($agent != ""  && $code != "" && $from_date != "" && $to_date == "",function($query) use ($code,$agent,$from_date,$to_date){
    //             $query->where('code',$code)
    //             ->where('user_id',$agent)
    //             ->where('created_at','>',date('Y-m-d', strtotime($from_date))." 00:00:00")->get();
    //         })
    //         ->when($agent != ""  && $code != "" && $from_date != "" && $to_date != "",function($query) use ($code,$agent,$from_date,$to_date){
    //             $query->where('code',$code)
    //             ->where('user_id',$agent)
    //             ->whereBetween('created_at', [ date('Y-m-d', strtotime($from_date))." 00:00:00",  date('Y-m-d', strtotime($to_date))." 23:59:59"])
    //             ->get();
    //         })
    //         ->when($fromserial != "" && $toserial != "",function($query) use ($fromserial,$toserial){
    //             $query->whereBetween('serial_no',[intval($fromserial),intval($toserial)])->get();
    //         })
    //         ->when($fromserial != "" && $toserial == "",function($query) use ($fromserial,$toserial){
    //             $query->where('serial_no','>=',intval($fromserial))->get();
    //         })
    //         ->where('is_active',1)
    //         ->orderBy('orders.id','ASC')
    //         ->select('id','thickness','length','width','quantity','design','code','remarks','status','user_id','serial_no')->get();
    //         if(!$orders->isEmpty()){
    //             $data=['orders'=>$orders,'from_date'=>$from_date,'to_date'=>$to_date,'fromserial'=>$fromserial,'toserial'=>$toserial];
    //             ini_set('memory_limit', '512M');               
    //             $pdf = PDF::loadView('order_pdf',$data);
    //             $report = 'report_'.rand(10,100).'.pdf';
    //             $pdf->save(public_path('/reports/'.$report));
    //             $file= public_path('/reports/'.$report);
    //                 // $headers = array(
    //                 //         'Content-Type: application/pdf',
    //                 //         );
        
    //             return Response::download($file, 'order.pdf');
    //         }else{
    //             Session::flash('error', 'No Data to Print');
    //             return redirect(url('/'));
    //         }
    //         return view('welcome', array('orders' => $orders,'from_date'=>$from_date,'to_date'=>$to_date,'fromserial'=>$fromserial,'toserial'=>$toserial,'selected_agent'=>$agent));
    //     }catch(Exception $e){
    //         return redirect(url('/'));
    //     }
    // }

    public function closing_stock_report()
    {
        try{
            $stocks = Stock::where('status',1)->orderBy('stocks.id','ASC')->paginate(20);     
            return view('reports.stock_report',compact('stocks'));
        }catch (Exception $ex) {
            return redirect('/');
        }
    }
    public function agent_wise_report()
    {
        try{
            $orders = Order::where('is_active',1)
            ->groupby('orders.user_id')
            ->paginate(20); 
            $report_arr = array();
            foreach($orders as $key=>$order){
                $received_count= Order::where('status',1)->where('is_active',1)->where('user_id',$order->user_id)->get()->count();
                $dispatched_count= Order::where('status',4)->where('is_active',1)->where('user_id',$order->user_id)->get()->count(); 
                $report_arr[]=array(
                    'name' => User::where('id',$order->user_id)->first()->fullname,
                    'received_count'=> $received_count,
                    'dispatched_count'=> $dispatched_count, 
                    'pending_count'=> $received_count-$dispatched_count
                );
            }    
            return view('reports.agentwise_report',compact('report_arr','orders'));
        }catch (Exception $ex) {
            return redirect('/');
        }
    }

    public function gate_pass_report()
    {
        try{
            $orders = Order::leftjoin('users','users.id','=','orders.user_id')
            ->where('is_active',1)
            ->where('status',4)
            ->select('orders.*','users.fullname')
            ->paginate(20); 
            return view('reports.gatepass_report',compact('orders'));
        }catch (Exception $ex) {
            return redirect('/');
        }
    }
    public function agent_report_search(Request $request) {
        $from_date = $request->get('fromdate')?$request->get('fromdate'):'';
        $to_date = $request->get('todate')?$request->get('todate'):'';
        $orders = DB::table('orders')
        ->when($from_date != "" && $to_date != "",function($query) use ($from_date,$to_date){
            $query->whereBetween('created_at', [ date('Y-m-d', strtotime($from_date))." 00:00:00",  date('Y-m-d', strtotime($to_date))." 23:59:59"])->get();
        })
        ->when($from_date != "" && $to_date == "",function($query) use ($from_date,$to_date){
            $query->where('created_at','>',date('Y-m-d', strtotime($from_date))." 00:00:00")->get();
        })
        ->where('is_active',1)
        ->groupby('orders.user_id')
        ->paginate(20);
        $report_arr = array();
        foreach($orders as $key=>$order){
            $received_count= Order::where('status',1)->where('is_active',1)->where('user_id',$order->user_id)->get()->count();
            $dispatched_count= Order::where('status',4)->where('is_active',1)->where('user_id',$order->user_id)->get()->count(); 
            $report_arr[]=array(
                'name' => User::where('id',$order->user_id)->first()->fullname,
                'received_count'=> $received_count,
                'dispatched_count'=> $dispatched_count, 
                'pending_count'=> $received_count-$dispatched_count
            );
        }    
        return view('reports/agentwise_report', array('report_arr' => $report_arr,'orders'=>$orders,'from_date'=>$from_date,'to_date'=>$to_date));
    }

}
