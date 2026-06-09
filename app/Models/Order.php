<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
