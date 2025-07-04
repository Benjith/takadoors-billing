@extends('layouts.outer')
@section('content')
    <!-- partial -->
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <form id="filtter">
                    {{-- @csrf --}}
                    <div class="input-group">
                        <div class="container">
                            <div class="dropdown dropdown-select">
                                <input type="date" id="fromDate" name="fromdate" value="{{ $from_date ?? '' }}"
                                    class="form-control" placeholder="From Date" aria-label="search"
                                    aria-describedby="search">
                            </div>
                            <div class="dropdown dropdown-select">
                                <input type="date" id="toDate" name="todate" value="{{ $to_date ?? '' }}"
                                    class="form-control" placeholder="To Date" aria-label="search"
                                    aria-describedby="search">
                            </div>
                            <div class="dropdown dropdown-select">
                                <input type="text" id="fromSerial" name="fromserial" value="{{ $fromserial ?? '' }}"
                                    class="form-control" placeholder="From Serial Number" aria-label="search"
                                    aria-describedby="search">
                            </div>
                            <div class="dropdown dropdown-select">
                                <input type="text" id="toSerial" name="toserial" value="{{ $toserial ?? '' }}"
                                    class="form-control" placeholder="To Serial Number" aria-label="search"
                                    aria-describedby="search">
                            </div>
                            <span class="msg"></span>
                            <div class="dropdown dropdown-select">
                                <input type="text" id="code" name="code" value="{{ $code ?? '' }}"
                                    class="form-control" placeholder="Enter Code" aria-label="search"
                                    aria-describedby="search">
                            </div>
                            <div class="dropdown dropdown-select">
                                <input type="text" id="sub" name="sub" value="{{ $sub ?? '' }}"
                                    class="form-control" placeholder="Enter sub" aria-label="search"
                                    aria-describedby="search">
                            </div>
                            <div class="dropdown-select agent-input">
                                <button class="btn btn-primary mt-2 mt-xl-0" type="submit">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="status_message"></div>
            @if (Session::has('error'))
                <div class="alert-danger flash-message"> <span> {{ Session::get('error') }} </span> </div>
            @endif
            @if (Session::has('success'))
                <div class="alert-success flash-message"> <span> {{ Session::get('success') }} </span> </div>
            @endif
            <div class="row mb-3">
                <div class="col-md-12 text-right">
                    {{-- <form id="printForm" method="GET" target="_blank" style="display: none;"></form> --}}

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
                                            <th>Color</th>
                                            <th>Code</th>
                                            <th>Sub</th>
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
    <script>
        $(document).ready(function() {
            $('#dispatch_table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 20,
                ajax: {
                    url: '{{ route('dispatch_search_ajax') }}',
                    type: 'GET',
                    data: function(d) {
                        d.fromdate = $('#fromDate').val();
                        d.todate = $('#toDate').val();
                        d.fromserial = $('#fromSerial').val();
                        d.toserial = $('#toSerial').val();
                        d.code = $('#code').val();
                        d.sub = $('#sub').val();
                    }
                },
                columns: [{
                        data: 'serial_no',
                        name: 'serial_no'
                    },
                    {
                        data: 'thickness',
                        name: 'thickness'
                    },
                    {
                        data: 'length',
                        name: 'length'
                    },
                    {
                        data: 'width',
                        name: 'width'
                    },
                    {
                        data: 'quantity',
                        name: 'quantity'
                    },
                    {
                        data: 'design',
                        name: 'design'
                    },
                    {
                        data: 'frame',
                        name: 'frame'
                    },
                    {
                        data: 'color',
                        name: 'color'
                    },
                    {
                        data: 'code',
                        name: 'code'
                    },
                    {
                        data: 'sub',
                        name: 'sub'
                    },
                    {
                        data: 'remarks',
                        name: 'remarks'
                    },
                ],
                columnDefs: [{
                    targets: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                    createdCell: function(td, cellData, rowData, row, col) {
                        const columnNames = ['thickness', 'length', 'width', 'quantity',
                            'design', 'frame', 'color', 'code', 'sub', 'remarks'
                        ];
                        const columnName = columnNames[col - 1];
                        $(td).attr('contenteditable', 'true')
                            .attr('data-column', columnName)
                            .attr('data-id', rowData.id);
                    }
                }]
            });
            // initializeDataTable();

            // Reinitialize DataTable after each pagination click
            // $(document).on('click', '.pagination a', function(event) {
            //     event.preventDefault();
            //     var url = $(this).attr('href');
            //     var params = {
            //         fromdate: $('#fromDate').val(),
            //         todate: $('#toDate').val(),
            //         fromserial: $('#fromSerial').val(),
            //         toserial: $('#toSerial').val(),
            //         code: $('#code').val()
            //     };
            //     $.get(url, params, function(data) {
            //         $('.content-wrapper').html($(data).find('.content-wrapper').html());
            //         initializeDataTable();
            //     });
            // });
            $('#filtter').on('submit', function(e) {
                e.preventDefault();
                $('#dispatch_table').DataTable().ajax.reload();
            })

            // Handle inline editing
            $(document).on('blur', '[contenteditable="true"]', function() {
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
            // $('#filter').on('submit', function(e) {
            //     e.preventDefault();
            //     dispatchTable.ajax.reload();
            // });
            $('#printButton').click(function() {
                var fromDate = $('#fromDate').val() || 'null';
                var toDate = $('#toDate').val() || 'null';
                var fromSerial = $('#fromSerial').val() || 'null';
                var toSerial = $('#toSerial').val() || 'null';
                var code = $('#code').val() || 'null';

                window.location.href =
                    `/dispatch/print/${fromDate}/${toDate}/${fromSerial}/${toSerial}/${code}`;
            });
        });
    </script>
