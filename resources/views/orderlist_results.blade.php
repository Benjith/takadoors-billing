<?php $count=0;?>
@foreach ($orders as $order)
<tr>
<td style="width:5%">{{$order->serial_no}}</td>

                            <td>{{$order->thickness}}</td>
                            <td>{{$order->length}}</td>
                            <td>{{$order->width}}</td>
                            <td>{{$order->quantity}}</td>
                            <td>{{$order->design}}</td>
                            <td>{{$order->code}}</td>
                            <td>{{strtoupper($order->remarks)}}</td>
                            <td><?php $user = App\Models\User::whereId($order->user_id)->first();$name= $user?$user->fullname:'';?>{{$name}}</td>                         
                            <td></td>                         
</tr>

@endforeach

