
@extends('layouts.outer')
@section('content') 
   <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          
          <div class="row">
            
          <form method="POST" action="{{ route('agent_search')}}">
                        @csrf
                  <div class="input-group">
                    <div class="col-xs-6">
                        <input type="date" id="fromDate" name="fromdate" value="<?php if(isset($from_date)) echo $from_date; ?>" class="form-control" placeholder="From Date" aria-label="search" aria-describedby="search">                      
                    </div>  
                    <div class="col-xs-6">
                        <input type="date" id="toDate" name="todate" value="<?php if(isset($to_date)) echo $to_date; ?>" class="form-control" placeholder="To Date" aria-label="search" aria-describedby="search">
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
                  <p class="card-title">AGENT WISE REPORT</p>
                  <div class="table-responsive">
                    <table id="recent-purchases-listing" class="table">
                      <thead>
                        <tr>
                            <th>SI.No.</th>
                            <th>Name</th>
                            <th>Order Received Count</th>
                            <th>Dispatched Count</th>
                            <th>Pending Count</th>
                        </tr>
                      </thead>
                      <tbody class="cus_results">
                      @include('reports/agentwise_report_list')
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