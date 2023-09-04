<?php $count=1;?>
@foreach ($orders as $order)
<tr>
<td style="width:5%">{{$count++}}</td>
    <td>{{$order->fullname}}</td>
    <td>{{$order->driver_name}}</td>
    <td>{{$order->route}}</td>
    <td>{{$order->design}}</td>
    <td>{{$order->quantity}}</td>
    <td>{{$order->remarks}}</td>
</tr>
@endforeach

