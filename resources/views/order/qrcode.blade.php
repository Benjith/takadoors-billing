<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laravel Generate QR Code Examples</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<style>
    .card{
        width: 43%;
    }
</style>
<body>
    <div class="container mt-4">
        <div class="card">
            <!-- <div class="card-header">
                <h4>Serial No: {{$order->serial_no}}</h2>
            </div> -->
            <div class="card-body">
                <b>Serial No : {{$order->serial_no}}</b>
                <img src="data:image/png;base64, {!! base64_encode(QrCode::size(200)->generate($order)) !!} "><br>
                {!! QrCode::size(200)->generate($order) !!}
                Length : {{$order->length}}
                Width : {{$order->width}}<br>
                Design : {{$order->design}}
                Code : {{$order->code}}

            </div>
        </div>
        <!-- <div class="card">
            <div class="card-header">
                <h2>Color QR Code</h2>
            </div>
            <div class="card-body">
                {!! QrCode::size(300)->backgroundColor(255,90,0)->generate('RemoteStack') !!}
            </div>
        </div> -->
    </div>
</body>
</html>