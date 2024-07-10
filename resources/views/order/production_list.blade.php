
@extends('layouts.outer')
@section('content') 
<style>
  .success-message {
    color: green;
}

.error-message {
    color: red;
}
@media print {
    .no-print {
        display: none;
    }
}
</style>
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
                  <p class="card-title">PRODUCTION LIST</p>
                  <div id="message-container"></div>
                  <!-- <div style="margin-left:750px;">
                    <button id="printButton" class="no-print" class="btn btn-primary mt-2 mt-xl-0" id="addRowLink">Print</button>    
                  </div> -->
                  <div id="print">
                              <a href="{{ URL::to('/production-print') }}"><button class="btn btn-primary mt-2 mt-xl-0">Print</button></a>
                </div> 
                </div>
                  <div class="table-responsive">
                  <form id="updateOrderForm">
                    <table id="orderTable" class="table">
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
                      <tbody>
                        <?php foreach ($orders as $row): ?>
                            <input value="<?= $row['id'] ?>"type="hidden" class="form-control" name="id[]"></input>
                            <tr>
                            <td><?= $row['serial_no'] ?></td>
                                <td><textarea class="form-control" name="thickness[]" rows="1" cols="20" data-row="<?= $row['id'] ?>" data-column="1"><?= $row['thickness'] ?></textarea></td>
                                    <td><textarea class="form-control" name="length[]" rows="1"cols="20" data-row="<?= $row['id'] ?>" data-column="2"><?= $row['length'] ?></textarea></td>
                                          <td><textarea class="form-control" name="width[]" rows="1"cols="40" data-row="<?= $row['id'] ?>" data-column="3"><?= $row['width'] ?></textarea></td>
                                          <td><textarea class="form-control" name="quantity[]" rows="1"cols="20" data-row="<?= $row['id'] ?>" data-column="4"><?= $row['quantity'] ?></textarea></td>
                                           <td><textarea class="form-control" name="design[]" rows="1" cols="30" data-row="<?= $row['id'] ?>" data-column="5"><?= $row['design'] ?></textarea></td>
                                           <td><textarea class="form-control" name="frame[]" rows="1" cols="30" data-row="<?= $row['id'] ?>" data-column="6"><?= $row['frame'] ?></textarea></td>
                                           <td><textarea class="form-control" name="code[]" rows="1" cols="30" data-row="<?= $row['id'] ?>" data-column="7"><?= $row['code'] ?></textarea></td>
                                           <td><textarea class="form-control" name="remarks[]" rows="1" cols="40" data-row="<?= $row['id'] ?>" data-column="8"><?= $row['remarks'] ?></textarea></td>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <!-- <tr>
                                          <td class="serial-number">1</td>
                                            <td><textarea class="form-control" name="thickness" rows="1" cols="20" data-row="1" data-column="1"></textarea></td>
                                            <td><textarea class="form-control" name="length" rows="1"cols="20" data-row="1" data-column="2"></textarea></td>
                                          <td><textarea class="form-control" name="width" rows="1"cols="20" data-row="1" data-column="3"></textarea></td>
                                          <td><textarea class="form-control" name="quantity" rows="1"cols="20" data-row="1" data-column="4"></textarea></td>
                                           <td><textarea class="form-control" name="design" rows="1" cols="30" data-row="1" data-column="5"></textarea></td>
                                           <td><textarea class="form-control" name="frame" rows="1" cols="30" data-row="1" data-column="6"></textarea></td>
                                           <td><textarea class="form-control" name="code" rows="1" cols="30" data-row="1" data-column="7"></textarea></td>
                                           <td><textarea class="form-control" name="remarks" rows="1" cols="40" data-row="1" data-column="8"></textarea></td>
                        </tr> -->
                      </tbody>
                    </table>
                    {{ $orders->links('pagination::bootstrap-4') }}

                    <div style="padding-top:30px;padding-bottom:40px;">
                    <button style="margin-left:800px;" class="btn btn-primary mt-2 mt-xl-0" type="submit" id="submitBtn">Update</button>
</div>
</form>
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
$(document).ready(function(){
    var index = 0;
    function reorderSerialNumbers() {
        $("#orderTable tbody tr").each(function(index) { 
            $(this).find('.serial-number').text(index + 1);
        });
    }
    
    $(document).on('keydown', '.form-control', function(e) {
        var arrowKeys = [37, 38, 39, 40]; // Left, Up, Right, Down arrow keys
        if (arrowKeys.includes(e.keyCode)) {
            e.preventDefault();
            var currentInput = $(this);
            var row = parseInt(currentInput.data('row'));
            var column = parseInt(currentInput.data('column'));
            var nextInput;
            switch (e.keyCode) {
                case 37: // Left arrow key
                    nextInput = $('[data-row="' + row + '"][data-column="' + (column - 1) + '"]');
                    break;
                case 38: // Up arrow key
                    nextInput = $('[data-row="' + (row - 1) + '"][data-column="' + column + '"]');
                    break;
                case 39: // Right arrow key
                    nextInput = $('[data-row="' + row + '"][data-column="' + (column + 1) + '"]');
                    if (!nextInput.length) {
                        // If the next column doesn't exist, navigate to the next row's first column
                        nextInput = $('[data-row="' + (row + 1) + '"][data-column="1"]');
                    }
                    break;
                case 40: // Down arrow key
                    nextInput = $('[data-row="' + (row + 1) + '"][data-column="' + column + '"]');
                    break;
            }
            if (nextInput.length) {
                nextInput.focus();
            }
        }
    });
   

    $(document).ready(function(){
        reorderSerialNumbers();
        $('#updateOrderForm').submit(function(event) {
            event.preventDefault(); // Prevent default form submission
            var formData = $(this).serialize(); // Serialize form data

            // Send AJAX request
            $.ajax({
                url: '{{ route("updateOrders") }}', // Route to your updateOrders method
                type: 'POST',
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Include CSRF token in headers
                },
                data: formData, // Send serialized form data
                success: function(response) {
                    if (response && response.message) {
                    $('#message-container').html('<p class="success-message">' + response.message + '</p>');
                    alert(response.message);
                    // location.reload();
                    } else {
                        $('#message-container').html('<p class="error-message">Something went wrong</p>');
                        console.error('Invalid response:', response);
                    }
                    // Handle success response
                    // console.log('AJAX request successful');
                    // console.log('Response:', response);
                    // Optionally, display a success message to the user
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    $('#message-container').html('<p class="error-message">Failed</p>');
                    console.error('AJAX request failed');
                    console.error('Error:', error);
                    // Optionally, display an error message to the user
                }
            });
        });
    });
});
</script>