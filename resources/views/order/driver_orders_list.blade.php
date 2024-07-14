
@extends('layouts.outer')
@section('content') 
   <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
                        @csrf
                  <div class="input-group">
                    <div class="col-xs-6 dropdown dropdown-select">
                         <input type="text" id="driver_name" name="driver" value="<?php if(isset($driver)) echo $driver; ?>" class="form-control" placeholder="Enter Driver Name" aria-label="search" aria-describedby="search">                          
                    </div>
                    <div class="col-xs-6 dropdown dropdown-select">
                      <input type="date" id="fromDate" name="fromdate" value="<?php if(isset($from_date)) echo $from_date; ?>" class="form-control" placeholder="From Date" aria-label="search" aria-describedby="search">                      
                    </div>
                    <div class="col-xs-6 dropdown dropdown-select">
                      <input type="date" id="toDate" name="todate" value="<?php if(isset($to_date)) echo $to_date; ?>" class="form-control" placeholder="To Date" aria-label="search" aria-describedby="search">
                    </div>
</div>
<div class="input-group">

                    <div class="col-xs-6 dropdown dropdown-select">
                      <input type="text" id="fromSerial" name="fromserial" value="<?php if(isset($fromserial)) echo $fromserial; ?>" class="form-control" placeholder="From Serial Number" aria-label="search" aria-describedby="search">
                    </div>
                    <div class="col-xs-6 dropdown dropdown-select">
                      <input type="text" id="toSerial" name="toserial" value="<?php if(isset($toserial)) echo $toserial; ?>" class="form-control" placeholder="To Serial Number" aria-label="search" aria-describedby="search">                                                  
                    </div>
</div>
<div class="input-group">

                    <div class="col-xs-6 dropdown dropdown-select">
                         <input type="text dropdown dropdown-select" id="search-input" name="code" value="<?php if(isset($code)) echo $code; ?>" class="form-control" placeholder="Enter Code" aria-label="search" aria-describedby="search">                          
                    </div>
                    <div class="col-xs-6 dropdown-select agent-input">
                        <button id="searchOrder" class="btn btn-primary mt-2 mt-xl-0">Submit</button>    
                    </div>
                    <div style="margin-left:350px;">                      
                          <div id="print">
                            <button id="customPrintButton" class="btn btn-primary mt-2 mt-xl-0">Print</button>
                          </div> 
                    </div>
                  </div>  
</div>
<br>
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
                  <p class="card-title">DISPATCH REPORT</p>
                  <div class="table-responsive">
                    <table id="driver_table" class="table">
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

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

<!-- DataTables Buttons CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.0.2/css/buttons.dataTables.min.css">

<!-- DataTables JavaScript -->
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<!-- DataTables Buttons JavaScript -->
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.2.2/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.2/js/buttons.html5.min.js"></script>

<script>
  
  $(document).ready(function() {
    var table = $('#driver_table').DataTable({
          processing: true,
          serverSide: true,
          "order": [],
          ajax: {
              url: "{{ route('getData') }}",
              type: "GET",
              data: function(d) {
                d.code = $('#search-input').val(); 
                d.fromdate = $('#fromDate').val(); 
                d.todate = $('#toDate').val(); 
                d.fromserial= $('#fromSerial').val(); 
                d.toserial = $('#toSerial').val(); 
              }
          },
          columns: [
            { data: 'serial_no', name: 'serial_no' },
            { data: 'thickness', name: 'thickness' },
            { data: 'length', name: 'length' },
            { data: 'width', name: 'width' },
            { data: 'quantity', name: 'quantity' },
            { data: 'design', name: 'design' },
            { data: 'frame', name: 'frame' },
            { data: 'code', name: 'code' },
            { data: 'remarks', name: 'remarks' }
        ],
          paging: true, 
          pageLength: 1000, 
          lengthMenu: [ [10, 25, 50, 100, 500, -1], [10, 25, 50, 100, 500, "All"] ], 
          initComplete: function(settings, json) {
              table.clear().draw(); // Ensure the table is empty on load
          }
      });

      $('#searchOrder').on('click', function() {
        alert('a');
        table.clear().draw();
        // table.ajax.reload();        
    });

    $('#customPrintButton').on('click', function() {
        if ($('#driver_table tbody tr').length === 0) {
            alert('The table does not have any data');
            return;
        }
        var driverName = $('#driver_name').val();
        if (driverName.trim() === '') {
            alert('Please enter the driver name');
            return;
        }
        var requestData = {
            driver_name: driverName
        };

        $.ajax({
            url: "{{ route('printDriverOrder') }}",
            method: 'POST', 
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
            },
            data: requestData,
            success:function(response){
              console.log(response);
              if (response.pdf1) {
                window.open('/reports/' + response.pdf1, '_blank');
              } 
              if (response.pdf2) {
                window.open('/reports/' + response.pdf2, '_blank');
              } 
              location.reload();               
            },
            error: function(blob) {
            console.log(blob);
            },
        });
    });

  });
</script> 