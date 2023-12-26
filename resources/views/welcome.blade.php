
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
                  <form method="post" action="{{route('search')}}">
                  {{ csrf_field() }}
                    <div class="container">
                        <div class="dropdown dropdown-select">
                          <input type="date" id="fromDate" name="fromdate" value="<?php if(isset($from_date)) echo $from_date; ?>" class="form-control" placeholder="From Date" aria-label="search" aria-describedby="search">                                                  
                        </div>
                        <div class="dropdown dropdown-select">
                          <input type="date" id="toDate" name="todate" value="<?php if(isset($to_date)) echo $to_date; ?>" class="form-control" placeholder="To Date" aria-label="search" aria-describedby="search">                    
                        </div>
                      <span class="msg"></span>
                    </div>
                    <div class="container">
                        <div class="dropdown dropdown-select">
                          <input type="text" id="fromSerial" name="fromserial" value="<?php if(isset($fromserial)) echo $fromserial; ?>" class="form-control" placeholder="From Serial Number" aria-label="search" aria-describedby="search">
                        </div>
                        <div class="dropdown dropdown-select">
                          <input type="text" id="toSerial" name="toserial" value="<?php if(isset($toserial)) echo $toserial; ?>" class="form-control" placeholder="To Serial Number" aria-label="search" aria-describedby="search">                          
                        </div>
                      <span class="msg"></span>
                    </div>
                    <div class="container">
                        <div class="dropdown dropdown-select">
                          <select id="agent"name="agent" class="form-control" style="height:39px;">
                            <option value="">Select Agent</option>
                            @foreach($agents as $key=>$agent)
                            <option <?php if($selected_agent == $agent->id ){ ?> selected="selected" <?php } ?> value="{{$agent->id}}">{{$agent->fullname}}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="dropdown dropdown-select">
                         <input type="text" id="code" name="code" value="<?php if(isset($code)) echo $code; ?>" class="form-control" placeholder="Enter Code" aria-label="search" aria-describedby="search">                          
                        </div>

                      <span class="msg"></span>
                    </div>
                    <div class="container">
                        <div class="dropdown-select">
                          <button type="submit" class="btn btn-primary mt-2 mt-xl-0 btn-submit">Submit</button>
                    </div>
                   
                      <span class="msg"></span>
                    </div>
                  </form>
                  </div>
                 
                </div>
            
                <div style="margin-top:180px">
                          <?php if($from_date == '')$from_date="null";if($to_date == '')$to_date="null";if($fromserial == '')$fromserial="null";if($toserial == '')$toserial="null";if($selected_agent == '')$selected_agent="null";if($code == '')$code="null"; ?>
                          <div id="print">
                              <a href="{{ URL::to('/print',['fromdate' => $from_date,'todate'=>$to_date,'fromserial'=>$fromserial,'toserial'=>$toserial,'agent'=>$selected_agent,'code'=>$code]) }}"><button class="btn btn-primary mt-2 mt-xl-0">Print</button></a>
                          </div> 
                </div>
                <!-- <div style="margin-top:180px">
                          <div>
                           <button id="btn-print" class="btn btn-primary mt-2 mt-xl-0 btn-print">Print</button>
                          </div> 
                </div> -->
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
                    <table id="orderTable" class="table">
                      <thead>
                        <tr>
                            <th style="display:none;"></th>
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
                        @include('orderlist_results',['orders'=>$orders])
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