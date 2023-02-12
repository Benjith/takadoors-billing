
@extends('layouts.outer')
@section('content') 
   <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="d-flex justify-content-between flex-wrap"  >
                <div class="d-flex align-items-end flex-wrap">
                  <div class="me-md-3 me-xl-5">
                  <form method="POST" action="{{ route('search')}}">
                        @csrf
                  <div class="input-group">
                    <div class="col-xs-6">
                        <input type="date" id="fromDate" name="fromdate" value="<?php if(isset($from_date)) echo $from_date; ?>" class="form-control" placeholder="From Date" aria-label="search" aria-describedby="search">                      
                    </div>  
                    <div class="col-xs-6">
                        <input type="date" id="toDate" name="todate" value="<?php if(isset($to_date)) echo $to_date; ?>" class="form-control" placeholder="To Date" aria-label="search" aria-describedby="search">
                    </div> 
                  </div>
                  <div class="input-group">
                    <div class="col-xs-6">
                        <input type="text" id="serialNo" name="fromserial" value="<?php if(isset($fromserial)) echo $fromserial; ?>" class="form-control" placeholder="From Serial Number" aria-label="search" aria-describedby="search">
                    </div> 
                    <div class="col-xs-6">
                        <input type="text" id="serialNo" name="toserial" value="<?php if(isset($toserial)) echo $toserial; ?>" class="form-control" placeholder="To Serial Number" aria-label="search" aria-describedby="search">
                    </div>  
                    <div class="col-xs-6">
                        <button class="btn btn-primary mt-2 mt-xl-0">Submit</button>    
                    </div>   
                  </div>             
                  </div>
</form>
                  </div>
                 <!-- <div class="d-flex">
                    <i class="mdi mdi-home text-muted hover-cursor"></i>
                    <p class="text-muted mb-0 hover-cursor">&nbsp;/&nbsp;Dashboard&nbsp;/&nbsp;</p>
                    <p class="text-primary mb-0 hover-cursor">Analytics</p>
                  </div> -->
                </div><?php if($from_date == '')$from_date="null";if($to_date == '')$to_date="null";if($fromserial == '')$fromserial="null";if($toserial == '')$toserial="null"; ?>
                <div class="d-flex justify-content-between align-items-end flex-wrap" id="print">
                    <a href="{{ URL::to('/print',['fromdate' => $from_date,'todate'=>$to_date,'fromserial'=>$fromserial,'toserial'=>$toserial]) }}"><button class="btn btn-primary mt-2 mt-xl-0">Print</button></a>
                </div>
              </div>
            </div>
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
                  <p class="card-title">List Order</p>
                  <div class="table-responsive">
                    <table id="recent-purchases-listing" class="table">
                      <thead>
                        <tr>
                            <th>SI.No.</th>
                            <th>Thick</th>
                            <th>Length</th>
                            <th>Width</th>
                            <th>No.s</th>
                            <th>Design</th>
                            <th>Code</th>
                            <th>Remarks</th>
                            <th>User</th>
                        </tr>
                      </thead>
                      <tbody class="cus_results">
                      @include('orderlist_results')
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