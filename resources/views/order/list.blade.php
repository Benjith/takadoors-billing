
@extends('layouts.outer')
@section('content') 
<style>
  .success-message {
    color: green;
}

.error-message {
    color: red;
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
                  <p class="card-title">CREATE ORDER</p>
                  <div id="message-container"></div>
                  <div style="margin-left:750px;">
                    <a class="btn btn-primary mt-2 mt-xl-0" id="addRowLink">Add Row</a>    
                  </div>
                </div>
                  <div class="table-responsive">
                  <form id="orderForm">
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
                        <tr>
                                          <td class="serial-number">1</td>
                                            <td><textarea class="form-control" name="thickness" rows="1" cols="20" data-row="1" data-column="1"></textarea></td>
                                            <td><textarea class="form-control" name="length" rows="1"cols="20" data-row="1" data-column="2"></textarea></td>
                                          <td><textarea class="form-control" name="width" rows="1"cols="20" data-row="1" data-column="3"></textarea></td>
                                          <td><textarea class="form-control" name="quantity" rows="1"cols="20" data-row="1" data-column="4"></textarea></td>
                                           <td><textarea class="form-control" name="design" rows="1" cols="30" data-row="1" data-column="5"></textarea></td>
                                           <td><textarea class="form-control" name="frame" rows="1" cols="30" data-row="1" data-column="6"></textarea></td>
                                           <td><textarea class="form-control" name="code" rows="1" cols="30" data-row="1" data-column="7"></textarea></td>
                                           <td><textarea class="form-control" name="remarks" rows="1" cols="40" data-row="1" data-column="8"></textarea></td>

                        </tr>
                      </tbody>
                    </table>
                    <div style="padding-top:30px;padding-bottom:40px;">
                    <button style="margin-left:800px;" class="btn btn-primary mt-2 mt-xl-0" type="submit" id="submitBtn">Submit</button>
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
    var datarow = 2;
    $("#addRowLink").click(function(){
        var lastRowCodeValue = $("#orderTable tbody tr:last textarea[name='code']").val();
        if(lastRowCodeValue == undefined){
          lastRowCodeValue = '';
        }
        var lastRowDesignValue = $("#orderTable tbody tr:last textarea[name='design']").val();
        if(lastRowDesignValue == undefined){
          lastRowDesignValue = '';
        }
        var lastRowFrameValue = $("#orderTable tbody tr:last textarea[name='frame']").val();
        if(lastRowFrameValue == undefined){
          lastRowFrameValue = '';
        }
        var newRow = '<tr><td class="serial-number">1</td><td><textarea class="form-control" name="thickness" rows="1" data-row="'+datarow+'" data-column="1"></textarea></td><td><textarea class="form-control" name="length" rows="1" data-row="'+datarow+'" data-column="2"></textarea></td><td><textarea name="width" class="form-control"  rows="1"data-row="'+datarow+'" data-column="3"></textarea></td><td><textarea class="form-control" name="quantity" rows="1" data-row="'+datarow+'" data-column="4"></textarea></td><td><textarea class="form-control" name="design" rows="1" data-row="'+datarow+'" data-column="5">'+lastRowDesignValue+'</textarea></td><td><textarea class="form-control" name="frame" rows="1" data-row="'+datarow+'" data-column="6">'+lastRowFrameValue+'</textarea></td><td><textarea class="form-control" name="code" rows="1" data-row="'+datarow+'" data-column="7">'+lastRowCodeValue+'</textarea></td><td><textarea class="form-control" name="remarks" rows="1" data-row="'+datarow+'" data-column="8"></textarea></td><td><a href="#" class="deleteRow"><i class="mdi mdi-delete"></i></a></td></tr>';
        $("#orderTable tbody").append(newRow);
        datarow++;
        reorderSerialNumbers();
    });
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
    $("#orderTable").on("click", ".deleteRow", function(){
        $(this).closest("tr").remove(); // Remove the closest row when the delete link is clicked
        reorderSerialNumbers();
    });

    $("#orderForm").submit(function(event) {
        event.preventDefault(); // Prevent default form submission

        // var isValid = true;
        // $("#orderTable tbody tr").each(function(index) {
        //     var isEmpty = false;
        //     $(this).find('textarea').each(function() {
        //         if ($(this).val().trim() == '') {
        //             isEmpty = true;
        //             return false; // Exit loop early if a non-empty field is found
        //         }
        //     });
        //     if (isEmpty) {
        //         isValid = false;
        //         return false; // Exit loop early if an empty row is found
        //     }
        // });

        // if (!isValid) {
        //     alert('Please fill in all required fields.');
        //     return; // Exit function if any required fields are empty
        // }

        var formData = [];

        // Iterate through each row in the table and collect data
        $("#orderTable tbody tr").each(function(index) {
            var rowData = {};

            // Find all textarea elements within the current row
            $(this).find('textarea').each(function() {
                var columnName = $(this).attr('name'); // Get the name attribute of the textarea
                var columnValue = $(this).val(); // Get the value of the textarea
                rowData[columnName] = columnValue; // Store the data in the rowData object
            });

            // Push the rowData object into the formData array
            formData.push(rowData);
          });
            $.ajax({
              url: '{{ route("order.bulk.create") }}', // Replace with your backend endpoint
              type: 'POST',
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Include CSRF token in headers
              },
              data: { formData: formData }, // Send the formData as JSON string
              success: function(response) {
                if (response && response.message) {
                    $('#message-container').html('<p class="success-message">' + response.message + '</p>');
                    location.reload();
                } else {
                    $('#message-container').html('<p class="error-message">Something went wrong</p>');
                    console.error('Invalid response:', response);
                }
                  // Handle successful AJAX call
                  console.log('AJAX request successful');
                  console.log('Response:', response);
              },
              error: function(xhr, status, error) {
                  // Handle AJAX call errors
                  $('#message-container').html('<p class="error-message">Something went wrong</p>');
                  console.error('AJAX request failed');
                  console.error('Error:', error);
              }
            });
        // Log the collected data (you can replace this with your actual form submission logic)
        console.log(formData);
    });
});
</script>