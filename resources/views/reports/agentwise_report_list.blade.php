<?php $count=1;?>
@foreach ($report_arr as $key=>$report)
<tr>
<td style="width:5%">{{$count++}}</td>
    <td>{{$report['name']}}</td>
    <td>{{$report['received_count']}}</td>
    <td>{{$report['dispatched_count']}}</td>
    <td>{{$report['pending_count']}}</td>
</tr>
@endforeach

