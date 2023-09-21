
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
                  <form>
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
                          <button class="btn btn-primary mt-2 mt-xl-0 btn-submit">Submit</button>
                    </div>
                   
                      <span class="msg"></span>
                    </div>
                  </form>
                  </div>
                 
                </div>
            
                <!-- <div style="margin-top:180px">
                          <?php if($from_date == '')$from_date="null";if($to_date == '')$to_date="null";if($fromserial == '')$fromserial="null";if($toserial == '')$toserial="null";if($selected_agent == '')$selected_agent="null";if($code == '')$code="null"; ?>
                          <div id="print">
                              <a href="{{ URL::to('/print',['fromdate' => $from_date,'todate'=>$to_date,'fromserial'=>$fromserial,'toserial'=>$toserial,'agent'=>$selected_agent,'code'=>$code]) }}"><button class="btn btn-primary mt-2 mt-xl-0">Print</button></a>
                          </div> 
                </div> -->
                <div style="margin-top:180px">
                          <div>
                           <button id="btn-print" class="btn btn-primary mt-2 mt-xl-0 btn-print">Print</button>
                          </div> 
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
          <div class="checkbox-container">
                      <input type="checkbox" id="clear_check">
                      <label for="clear_check">Clear Table Data</label>
          </div> 
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



<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
<script src="//code.jquery.com/jquery-1.12.4.js"></script>

<script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    submitClick();
    // $('#clear_check').val(this.checked);
    $('#clear_check').change(function() {
        if(this.checked) {
            var returnVal = confirm("Are you sure?");
            if(returnVal == true){
              var table = $('#orderTable').DataTable();
              table.clear().draw();
            }
            $(this).prop("checked", false);
        }
        // $('#textbox1').val(this.checked);        
    });
});



$(".btn-submit").click(function(e){
  e.preventDefault();
  submitClick();
});
$(".btn-print").click(function(e){
  e.preventDefault();
  var table = $('#orderTable').DataTable();
  var orderList = [];
  table.rows().every( function () {
    var d = this.data();
    var item = {};
    item['serial_no'] = d['1'];
    item['thickness'] = d['2'];
    item['length'] = d['3'];
    item['width'] = d['4'];
    item['quantity'] = d['5'];
    item['design'] = d['6'];
    item['code'] = d['7'];
    item['remarks'] = d['8'];
    item['username'] = d['9'];
    orderList.push(item);
  });

    var fromDate = $('#fromDate').val();
    var toDate = $('#toDate').val();
    var fromSerial = $('#fromSerial').val();
    var toSerial = $('#toSerial').val();
    var code = $('#code').val();
    var agent = $('#agent').val();
    $.ajax({
      url: "{{ route('print')}}",
      type:"POST",
      data:{
        "_token": "{{ csrf_token() }}",
        fromdate:fromDate,
        todate:toDate,
        fromserial:fromSerial,
        toserial:toSerial,
        agent:agent,
        code:code,
        orderList:orderList,
      },
      xhrFields: {
                responseType: 'blob'
      },
      success:function(response){
        var blob = new Blob([response]);
        var link = document.createElement('a');
        link.href = window.URL.createObjectURL(blob);
        link.download = "order.pdf";
        link.click();     
      },
      error: function(blob) {
       console.log(blob);
      },
      });
});

function submitClick(){
    var fromDate = $('#fromDate').val();
    var toDate = $('#toDate').val();
    var fromSerial = $('#fromSerial').val();
    var toSerial = $('#toSerial').val();
    var code = $('#code').val();
    var agent = $('#agent').val();
    $.ajax({
      url: "{{ route('search')}}",
      type:"POST",
      data:{
        "_token": "{{ csrf_token() }}",
        fromdate:fromDate,
        todate:toDate,
        fromserial:fromSerial,
        toserial:toSerial,
        agent:agent,
        code:code,
      },
      success:function(response){
        $('#orderTable').DataTable();
        var dataTable = $("#orderTable").dataTable().api();
        $.each(response['response']['data'], function(key, val) {
         
           tr = document.createElement("tr");
           tr.innerHTML = '<tr>' +'<td style="display:none;" contenteditable="true">' + '?' + '<td contenteditable="true">' + val['serial_no'] + '</td>' + 
          '<td contenteditable="true">' + val['thickness'] + '</td>' +'<td contenteditable="true">' + val['length'] + '</td>' +'<td contenteditable="true">' + val['width'] + '</td>' +
          '<td contenteditable="true">' + val['quantity'] + '</td>' +'<td contenteditable="true">' + val['design'] + '</td>' +
          '<td contenteditable="true">' + val['code'] + '</td>' +'<td contenteditable="true">' + val['remarks'] + '</td>' +
          '<td contenteditable="true">' + val['username'] + '</td>' +'</tr>';
          // tr = $('#orderTable > tbody:last-child').append('<tr>' + '<td contenteditable="true">' + val['serial_no'] + '</td>' + 
          // '<td contenteditable="true">' + val['thickness'] + '</td>' +'<td contenteditable="true">' + val['length'] + '</td>' +'<td contenteditable="true">' + val['width'] + '</td>' +
          // '<td contenteditable="true">' + val['quantity'] + '</td>' +'<td contenteditable="true">' + val['design'] + '</td>' +
          // '<td contenteditable="true">' + val['code'] + '</td>' +'<td contenteditable="true">' + val['remarks'] + '</td>' +
          // '<td contenteditable="true">' + val['username'] + '</td>' +'</tr>');
          
            // console.log(val);
            // table.append('<tr>' + '<td contenteditable="true">' + value + '</td>' + '</tr>');
            // $('#orderTable > tbody:last-child').append(tr.innerHTML);
            dataTable.row.add(tr);
            dataTable.draw();
        });
       
      },
      error: function(response) {
       
      },
      });
}
</script>
@endsection
