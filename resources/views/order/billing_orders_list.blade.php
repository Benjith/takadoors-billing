
@extends('layouts.outer')
@section('content') 
   <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
        <div class="row">
            
            <form method="POST" action="{{ route('billing_search')}}">
                          @csrf
                    <div class="input-group">
                      <div class="col-xs-6">
                          <input type="date" id="fromDate" name="fromdate" value="<?php if(isset($from_date)) echo $from_date; ?>" class="form-control" placeholder="From Date" aria-label="search" aria-describedby="search">                      
                      </div>  
                      <div class="col-xs-6">
                          <input type="date" id="toDate" name="todate" value="<?php if(isset($to_date)) echo $to_date; ?>" class="form-control" placeholder="To Date" aria-label="search" aria-describedby="search">
                      </div> 
                      <div class="col-xs-6">
                           <input type="text" id="code" name="code" value="<?php if(isset($code)) echo $code; ?>" class="form-control" placeholder="Enter Code" aria-label="search" aria-describedby="search">                          
                      </div>
                      <div class="col-xs-6 agent-input">
                          <button class="btn btn-primary mt-2 mt-xl-0">Submit</button>    
                      </div>
                    </div>                    
          </form>
  </div>
          <div class="status_message"></div>
          @if(Session::has('error'))
          <div class="alert-danger flash-message" >  <span> {{ Session::get('error') }} </span>   </div>
          @endif
          @if (Session::has('success'))
          <div class="alert-success flash-message" >  <span> {{ Session::get('success') }} </span>   </div>
          @endif
          <div class="row">
            <div class="col-md-12 stretch-card">
              <div class="card">
                <div class="card-body">
                  <p class="card-title">BILLING REPORT</p>
                  <div class="table-responsive">
                    <table id="dispatch_table" class="table">
                      <thead>
                        <tr>
                            <th>SI.No.</th>
                            <th>Thickness</th>
                            <th>Length</th>
                            <th>Width</th>
                            <th>Quantity</th>
                            <th>Design</th>
                            <th> PVC Model  </th>
                            <th> Code </th>
                            <th>Remarks</th>
                        </tr>
                      </thead>
                      <tbody class="cus_results">
                      <?php $count=1;?>
                        @foreach ($orders as $order)
                        <tr>
                        <td style="width:5%">{{$order->serial_no}}</td>
                            <td>{{$order->thickness}}</td>
                            <td>{{$order->length}}</td>
                            <td>{{$order->width}}</td>
                            <td>{{$order->quantity}}</td>
                            <td>{{$order->design}}</td>
                            <td>{{$order->frame}}</td>
                            <td>{{$order->code}}</td>
                            <td>{{$order->remarks}}</td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#dispatch_table').DataTable({
        "order": [], // Disable automatic sorting
        paging: false,
    });
    
});
</script>