<?php $count=1;?>
@foreach ($stocks as $stock)
<tr>
<td style="width:5%">{{$count++}}</td>
    <td>{{$stock->design}}</td>
    <td>{{$stock->quantity}}</td>
</tr>
@endforeach

