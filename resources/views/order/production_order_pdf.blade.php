 
<div class="container">  
<h3 style="text-align: center;" class="heading-section">PRODUCTION ORDER LIST</h3>
    <br/> 
    @php
    use Carbon\Carbon;
    @endphp
    <p><b>Date:</b> <b>{{ Carbon::now()->format('d-m-Y') }}</b></p>
    <table style="width: 100%; background: #f7f7f7;">  
        <tr>  
        <th>SI.No.</th>
                            <th>Thick</th>
                            <th>Length</th>
                            <th>Width</th>
                            <th>No.s</th>
                            <th>Design</th>
                            <th>PVC Model </th>
                            <th>Code</th>
                            <th>Remarks</th>
        </tr> <?php $quantity = 0; ?> 
        @foreach ($orders as $key => $order)
        @if (is_numeric($quantity) && is_int((int)$quantity))
        <?php $quantity = $quantity+$order->quantity;?>
        @endif
        <tr>  
        <td>{{$order->serial_no}}</td>
        <td>{{$order->thickness}}</td>
                            <td>{{$order->length}}</td>
                            <td>{{$order->width}}</td>
                            <td>{{$order->quantity}}</td>
                            <td>{{$order->design}}</td>
                            <td>{{$order->frame}}</td>
                            <td>{{$order->code}}</td>
                            <td>{{strtoupper($order->remarks)}}</td>                

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