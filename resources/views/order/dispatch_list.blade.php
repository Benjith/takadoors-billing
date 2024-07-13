@extends('layouts.outer')
@section('content') 
   <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <form method="POST" action="{{ route('dispatch_search')}}">
                @csrf
                <div class="input-group">
                    <div class="container">
                      <div class="dropdown dropdown-select">
                          <input type="date" id="fromDate" name="fromdate" value="{{ $from_date ?? '' }}" class="form-control" placeholder="From Date" aria-label="search" aria-describedby="search">                      
                      </div>  
                      <div class="dropdown dropdown-select">
                          <input type="date" id="toDate" name="todate" value="{{ $to_date ?? '' }}" class="form-control" placeholder="To Date" aria-label="search" aria-describedby="search">
                      </div> 
                        <div class="dropdown dropdown-select">
                          <input type="text" id="fromSerial" name="fromserial" value="{{ $fromserial ?? '' }}" class="form-control" placeholder="From Serial Number" aria-label="search" aria-describedby="search">
                        </div>
                        <div class="dropdown dropdown-select">
                          <input type="text" id="toSerial" name="toserial" value="{{ $toserial ?? '' }}" class="form-control" placeholder="To Serial Number" aria-label="search" aria-describedby="search">                          
                        </div>
                        <span class="msg"></span>
                        <div class="dropdown dropdown-select">
                          <input type="text" id="code" name="code" value="{{ $code ?? '' }}" class="form-control" placeholder="Enter Code" aria-label="search" aria-describedby="search">                          
                        </div>
                        <div class="dropdown-select agent-input">
                            <button class="btn btn-primary mt-2 mt-xl-0">Submit</button>    
                        </div>
                    </div>
                </div>                    
            </form>
          </div>
          <div class="status_message"></div>
          @if(Session::has('error'))
            <div class="alert-danger flash-message">  <span> {{ Session::get('error') }} </span>   </div>
          @endif
          @if (Session::has('success'))
            <div class="alert-success flash-message">  <span> {{ Session::get('success') }} </span>   </div>
          @endif
          <div class="row">
            <div class="col-md-12 stretch-card">
              <div class="card">
                <div class="card-body">
                  <p class="card-title">DISPATCH REPORT</p>
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
                            <th>PVC Model</th>
                            <th>Code</th>
                            <th>Remarks</th>
                        </tr>
                      </thead>
                      <tbody class="cus_results">
                      @foreach ($orders as $order)
                        <tr>
                          <td style="width:5%">{{ $order->serial_no }}</td>
                          <td>{{ $order->thickness }}</td>
                          <td>{{ $order->length }}</td>
                          <td>{{ $order->width }}</td>
                          <td>{{ $order->quantity }}</td>
                          <td>{{ $order->design }}</td>
                          <td>{{ $order->frame }}</td>
                          <td>{{ $order->code }}</td>
                          <td>{{ $order->remarks }}</td>
                        </tr>
                      @endforeach
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function initializeDataTable() {
    $('#dispatch_table').DataTable({
        paging: false, // Disable DataTables pagination
        ordering: true, // Enable column ordering
        info: false, // Disable the info text
        destroy: true, // Allow reinitialization
    });
}

$(document).ready(function() {
    initializeDataTable();
    
    // Reinitialize DataTable after each pagination click
    $(document).on('click', '.pagination a', function(event) {
        event.preventDefault();
        var url = $(this).attr('href');
        $.get(url, function(data) {
            $('.content-wrapper').html($(data).find('.content-wrapper').html());
            initializeDataTable();
        });
    });
});
</script>
