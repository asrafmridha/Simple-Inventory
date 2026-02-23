<?php

namespace App\Models;

use Faker\Provider\Payment;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $guarded = [];


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function payment()
    {
        return $this->hasOne(SalePayment::class);
    }


    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
}
