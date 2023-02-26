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
            $from_date = "null";
            $to_date = "null";
            $fromserial = ""; 
            $toserial = "";     
            return view('welcome',compact('orders','from_date','to_date','fromserial','toserial'));
        }catch (Exception $ex) {
            return redirect('/');
        }
    }

    public function search(Request $request) {
        $from_date = $request->get('fromdate')?$request->get('fromdate'):'';
        $to_date = $request->get('todate')?$request->get('todate'):'';
        $fromserial = $request->get('fromserial')?$request->get('fromserial'):'';
        $toserial = $request->get('toserial')?$request->get('toserial'):'';
        $orders = DB::table('orders')
        ->when($from_date != "" && $to_date != "",function($query) use ($from_date,$to_date){
            $query->whereBetween('created_at', [ date('Y-m-d', strtotime($from_date))." 00:00:00",  date('Y-m-d', strtotime($to_date))." 23:59:59"])->get();
        })
        ->when($from_date != "" && $to_date == "",function($query) use ($from_date,$to_date){
            $query->where('created_at','>',date('Y-m-d', strtotime($from_date))." 00:00:00")->get();
        })
        ->when($fromserial != "" && $toserial != "",function($query) use ($fromserial,$toserial){
            $query->whereBetween('serial_no',[intval($fromserial),intval($toserial)])->get();
        })
        ->when($fromserial != "" && $toserial == "",function($query) use ($fromserial,$toserial){
            $query->where('serial_no','>=',intval($fromserial))->get();
        })
        ->where('is_active',1)
        ->orderBy('orders.id','ASC')
        ->select('id','thickness','length','width','quantity','design','code','remarks','status','user_id','serial_no')->paginate(20);
        return view('welcome', array('orders' => $orders,'from_date'=>$from_date,'to_date'=>$to_date,'fromserial'=>$fromserial,'toserial'=>$toserial));
    }
    
    public function print(Request $request,$from_date,$to_date,$fromserial,$toserial) {
        try{      
            if($from_date == "null"){
                $from_date = '';
            }
            if($to_date == "null"){
                $to_date = '';
            }
            if($fromserial == "null"){
                $fromserial = '';
            }
            if($toserial == "null"){
                $toserial = '';
            }
            $orders = DB::table('orders')
            ->when($from_date != "" && $to_date != "",function($query) use ($from_date,$to_date){
                $query->whereBetween('created_at', [ date('Y-m-d', strtotime($from_date))." 00:00:00",  date('Y-m-d', strtotime($to_date))." 23:59:59"])->get();
            })
            ->when($from_date != "" && $to_date == "",function($query) use ($from_date,$to_date){
                $query->where('created_at','>',date('Y-m-d', strtotime($from_date))." 00:00:00")->get();
            })
            ->when($fromserial != "" && $toserial != "",function($query) use ($fromserial,$toserial){
                $query->whereBetween('serial_no', [intval($fromserial),intval($toserial)])->get();
            })
            ->when($fromserial != "" && $toserial == "",function($query) use ($fromserial,$toserial){
                $query->where('serial_no','>=',intval($fromserial))->get();
            })
            ->where('is_active',1)
            ->orderBy('orders.id','ASC')
            ->select('id','thickness','length','width','quantity','design','code','remarks','status','user_id','serial_no')->get();
            if(!$orders->isEmpty()){
                $data=['orders'=>$orders,'from_date'=>$from_date,'to_date'=>$to_date,'fromserial'=>$fromserial,'toserial'=>$toserial];
                ini_set('memory_limit', '512M');               
                $pdf = PDF::loadView('order_pdf',$data);
                $report = 'report_'.rand(10,100).'.pdf';
                $pdf->save(public_path('/reports/'.$report));
                $file= public_path('/reports/'.$report);
                    // $headers = array(
                    //         'Content-Type: application/pdf',
                    //         );
        
                return Response::download($file, 'order.pdf');
            }else{
                Session::flash('error', 'No Data to Print');
                return redirect(url('/'));
            }
            return view('welcome', array('orders' => $orders,'from_date'=>$from_date,'to_date'=>$to_date,'fromserial'=>$fromserial,'toserial'=>$toserial));
        }catch(Exception $e){
            return redirect(url('/'));
        }
    }

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
            foreach($orders as $key=>$order){
                $received_count= Order::where('status',1)->where('is_active',1)->where('user_id',$order->user_id)->get()->count();
                $dispatched_count= Order::where('status',3)->where('is_active',1)->where('user_id',$order->user_id)->get()->count(); 
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

}
