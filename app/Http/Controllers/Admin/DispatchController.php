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
use 


DataTables;
use Carbon\Carbon;

class DispatchController extends Controller
{
    public function update(Request $request)
    {
        
        $order = Order::find($request->id);

        if ($order) {
            $order->{$request->column} = $request->value;
            $order->save();

            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }

    public function print(Request $request, $from_date, $to_date, $fromserial, $toserial, $code) {
        try {  
            // Handling null values
            $from_date = $from_date === "null" ? '' : $from_date;
            $to_date = $to_date === "null" ? '' : $to_date;
            $fromserial = $fromserial === "null" ? '' : $fromserial;
            $toserial = $toserial === "null" ? '' : $toserial;
            $code = $code === "null" ? '' : $code;
    
            // Building the query with conditional clauses
            $orders = DB::table('orders')
                ->leftJoin('users', 'users.id', '=', 'orders.user_id')
                ->when($from_date && $to_date, function($query) use ($from_date, $to_date) {
                    $query->whereBetween('orders.created_at', [
                        date('Y-m-d', strtotime($from_date))." 00:00:00", 
                        date('Y-m-d', strtotime($to_date))." 23:59:59"
                    ]);
                })
                ->when($from_date && !$to_date, function($query) use ($from_date) {
                    $query->where('orders.created_at', '>', date('Y-m-d', strtotime($from_date))." 00:00:00");
                })
                ->when($code, function($query) use ($code) {
                    $query->where('code', $code);
                })
                ->when($fromserial && $toserial, function($query) use ($fromserial, $toserial) {
                    $query->whereBetween('serial_no', [intval($fromserial), intval($toserial)]);
                })
                ->when($fromserial && !$toserial, function($query) use ($fromserial) {
                    $query->where('serial_no', '>=', intval($fromserial));
                })
                ->where('is_active', 1)
                ->where('status', 3)
                ->orderBy('orders.id', 'ASC')
                ->select(
                    'orders.id', 'thickness', 'length', 'width', 
                    'users.fullname as username', 'quantity', 'design', 
                    'code', 'remarks', 'status', 'user_id', 'serial_no'
                )
                ->get();
    
            // Check if any orders were found
            if (!$orders->isEmpty()) {
                $data = [
                    'orders' => $orders,
                    'from_date' => $from_date,
                    'to_date' => $to_date,
                    'fromserial' => $fromserial,
                    'toserial' => $toserial
                ];
    
                ini_set('memory_limit', '512M');  // Increase memory limit if necessary
                $pdf = PDF::loadView('order_pdf', $data);  // Load view into PDF
    
                $report = 'report_' . rand(10, 100) . '.pdf';  // Generate random filename
                $pdf->save(public_path('/reports/' . $report));  // Save the PDF file
    
                $file = public_path('/reports/' . $report);
    
                // Return the PDF as a download
                return response()->download($file, 'order.pdf');
            } else {
                // If no data, return an error message
                Session::flash('error', 'No Data to Print');
                return redirect(url('/'));
            }
        } catch (Exception $e) {
            return redirect(url('/'))->with('error', 'An error occurred while generating the report.');
        }
    }
    

}