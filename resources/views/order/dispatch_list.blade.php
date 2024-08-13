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
           <div class="row mb-3">
                <div class="col-md-12 text-right">
                    <button class="btn btn-secondary" id="printButton">Print</button>
                </div>
            </div>
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
                                           <td contenteditable="true" data-column="thickness" data-id="{{ $order->id }}">{{ $order->thickness }}</td>
                                           <td contenteditable="true" data-column="length" data-id="{{ $order->id }}">{{ $order->length }}</td>
                                           <td contenteditable="true" data-column="width" data-id="{{ $order->id }}">{{ $order->width }}</td>
                                           <td contenteditable="true" data-column="quantity" data-id="{{ $order->id }}">{{ $order->quantity }}</td>
                                           <td contenteditable="true" data-column="design" data-id="{{ $order->id }}">{{ $order->design }}</td>
                                           <td contenteditable="true" data-column="frame" data-id="{{ $order->id }}">{{ $order->frame }}</td>
                                           <td contenteditable="true" data-column="code" data-id="{{ $order->id }}">{{ $order->code }}</td>
                                           <td contenteditable="true" data-column="remarks" data-id="{{ $order->id }}">{{ $order->remarks }}</td>
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
        paging: false,
        ordering: true,
        info: false,
        destroy: true,
    });
}

$(document).ready(function() {
    initializeDataTable();

    // Reinitialize DataTable after each pagination click
    $(document).on('click', '.pagination a', function(event) {
        event.preventDefault();
        var url = $(this).attr('href');
        var params = {
            fromdate: $('#fromDate').val(),
            todate: $('#toDate').val(),
            fromserial: $('#fromSerial').val(),
            toserial: $('#toSerial').val(),
            code: $('#code').val()
        };
        $.get(url, params, function(data) {
            $('.content-wrapper').html($(data).find('.content-wrapper').html());
            initializeDataTable();
        });
    });

    // Handle inline editing
    $('[contenteditable="true"]').on('blur', function() {
        var id = $(this).data('id');
        var column = $(this).data('column');
        var value = $(this).text();
        console.log(id);
        $.ajax({
            url: '{{ route('dispatch.update') }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                id: id,
                column: column,
                value: value
            },
            success: function(response) {
                if (response.success) {
                  showMessage('success', 'Data updated successfully!');
                } else {
                  showMessage('danger', 'Error updating data!');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Show error message to the user
                 // Log detailed error information to the console
                console.log('AJAX request failed:', textStatus, errorThrown);
                console.log('Response:', jqXHR.responseText);
                showMessage('danger', 'Error updating data!');
               
            }
        });
    });
    function showMessage(type, message) {
        $('.status_message').html(`<div class="alert alert-${type}">${message}</div>`);

        // Hide the message after 5 seconds
        setTimeout(function() {
            $('.status_message .alert').fadeOut('slow', function() {
                $(this).remove();
            });
        }, 5000);
    }
    $('#printButton').click(function() {
        var fromDate = $('#fromDate').val() || 'null';
        var toDate = $('#toDate').val() || 'null';
        var fromSerial = $('#fromSerial').val() || 'null';
        var toSerial = $('#toSerial').val() || 'null';
        var code = $('#code').val() || 'null';
        
        window.location.href = `/print/${fromDate}/${toDate}/${fromSerial}/${toSerial}/${code}`;
    });
});
</script>
