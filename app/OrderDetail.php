<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $table = 'order_details';

    protected $fillable = [
        'order_no',
        'product_id',
        'product_name',
        'sku',
        'unit_price',
        'quantity',
        'line_total',
        'payment_method',
        'customer_name',
        'notes',
    ];
}
