<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'code','design','length','width','quantity','frame','remarks','thickness','user_id','last_modified_user_id','stock_id','status','serial_no'
    ];
}
