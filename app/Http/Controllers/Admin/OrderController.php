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

   public function dispatchSearch(Request $request) {
    $from_date = $request->get('fromdate')?$request->get('fromdate'):'';
    $to_date = $request->get('todate')?$request->get('todate'):'';
    $code = $request->get('code')?$request->get('code'):'';
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
    ->where('is_active',1)
    ->where('status',3)
    ->orderBy('orders.id','ASC')
    ->paginate(20);
    return view('order.dispatch_list', array('code'=>$code,'orders' => $orders,'from_date'=>$from_date,'to_date'=>$to_date));
}
}