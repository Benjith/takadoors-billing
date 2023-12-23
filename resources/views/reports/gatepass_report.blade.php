
@extends('layouts.outer')
@section('content') 
   <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          
          <div class="row">
            
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
                  <p class="card-title">GATE PASS REPORT</p>
                  <div class="table-responsive">
                    <table id="recent-purchases-listing" class="table">
                      <thead>
                        <tr>
                            <th>SI.No.</th>
                            <th>Agent Name</th>
                            <th>Driver Name</th>
                            <th>Route</th>
                            <th>Door Item</th>
                            <th>Quantity</th>
                            <th>Remarks</th>
                        </tr>
                      </thead>
                      <tbody class="cus_results">
                      @include('reports/gatepass_report_list')
                      </tbody>
                    </table>
{{ $orders->links('pagination::bootstrap-4') }}

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->




@endsection