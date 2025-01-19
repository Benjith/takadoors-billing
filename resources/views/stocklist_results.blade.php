<?php $count=1;?>
@foreach ($materials as $mat)
<tr>
<td style="width:5%">{{$count++}}</td>
<td>{{$mat->name}}</td>
<td>{{$mat->total}}</td>
                                              
</tr>

@endforeach

