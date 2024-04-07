
@extends('layouts.outer')
@section('content') 
   <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
         
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
                  <p class="card-title">BILLING REPORT(LAST MONTH)</p>
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