 
<div class="container">  
<h3 style="text-align: center;" class="heading-section">GATEPASS LIST</h3>
    <br/> 
    <p><b>Driver Name:</b> {{$driverName}} </p>
    <table style="width: 100%; background: #f7f7f7;">  
        <tr>  
                          
                            <th>Code</th>
                            <th>No.s</th>
        </tr>
         <?php $Quantity = 0; ?> 
        @foreach ($orders as $code => $totalQuantity)
            <tr>
                <td>{{ $code }}</td>
                <td>{{ $totalQuantity }}</td>
            </tr>
            @php
                $Quantity += $totalQuantity; // Add current order's quantity to total
            @endphp
        @endforeach 
    </table>
   <p><b>Total Quantity : </b><?php if(isset($Quantity)) echo $Quantity; ?></p>

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