 
<div class="container">  
<h3 style="text-align: center;" class="heading-section">ORDER LIST</h3>
    <br/> 
   <p>From : <?php if(isset($from_date) && $from_date != null) echo date('d-m-Y', strtotime($from_date)); ?></p>
   <p>To : <?php if(isset($to_date) && $to_date != null) echo date('d-m-Y', strtotime($to_date)); ?></p>
   <p>From Serial No :<?php if(isset($fromserial)) echo $fromserial; ?></p>
   <p>To Serial No :<?php if(isset($toserial)) echo $toserial; ?></p>

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
                            <th>User</th>
        </tr> <?php $quantity = 0; ?> 
        @foreach ($orders as $key => $order)
        <?php $quantity = $quantity+$order->quantity;?>
        <tr>  
        <td>{{$order->serial_no}}</td>
        <td>{{$order->thickness}}</td>
                            <td>{{$order->length}}</td>
                            <td>{{$order->width}}</td>
                            <td>{{$order->quantity}}</td>
                            <td>{{$order->design}}</td>
                            <td>{{$order->code}}</td>
                            <td>{{strtoupper($order->remarks)}}</td>
                            <td>{{$order->username}}</td>                         

        </tr> 
        @endforeach 
    </table>
   <p><b>Total Quantity : </b><?php if(isset($quantity)) echo $quantity; ?></p>

</div>  
<style type="text/css">  
    table, td,tr,th{  
        border:1px solid black; 
        border-collapse: collapse;
    }
    td{
        text-align: center;
    }  
</style> 