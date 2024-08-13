<!DOCTYPE html>
<html>
<head>
    <title>Order List</title>
    <style>
        table, td, tr, th {
            border: 1px solid black;
            border-collapse: collapse;
        }
        td {
            text-align: center;
        }
        .container {
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3 style="text-align: center;" class="heading-section">ORDER LIST</h3>
        <br/>
        <p>From: {{ isset($from_date) && $from_date ? date('d-m-Y', strtotime($from_date)) : 'N/A' }}</p>
        <p>To: {{ isset($to_date) && $to_date ? date('d-m-Y', strtotime($to_date)) : 'N/A' }}</p>
        <p>From Serial No: {{ isset($fromserial) ? $fromserial : 'N/A' }}</p>
        <p>To Serial No: {{ isset($toserial) ? $toserial : 'N/A' }}</p>

        <table style="width: 100%; background: #f7f7f7;">
            <tr>
                <th>SI.No.</th>
                <th>Thick</th>
                <th>Length</th>
                <th>Width</th>
                <th>No.s</th>
                <th>Design</th>
                <th>Code</th>
                <th>Remarks</th>
            </tr>
            @php
                $quantity = 0;
            @endphp
            @foreach ($orders as $order)
                @php
                    // Ensure $order->quantity is treated as an integer
                    $orderQuantity = (int)$order->quantity;
                    $quantity += $orderQuantity;
                @endphp
                <tr>
                    <td>{{ $order->serial_no }}</td>
                    <td>{{ $order->thickness }}</td>
                    <td>{{ $order->length }}</td>
                    <td>{{ $order->width }}</td>
                    <td>{{ $orderQuantity }}</td>
                    <td>{{ $order->design }}</td>
                    <td>{{ $order->code }}</td>
                    <td>{{ strtoupper($order->remarks) }}</td>
                </tr>
            @endforeach
        </table>
        <p><b>Total Quantity:</b> {{ $quantity }}</p>
    </div>
</body>
</html>
