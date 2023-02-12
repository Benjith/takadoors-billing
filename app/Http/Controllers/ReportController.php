<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;
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
                ini_set('memory_limit', '256M');               
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
}
