<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $dates = ['prevision', 'deleted_at'];

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function client()
    {
        return $this->hasOne('App\Models\Client', 'id', 'client_id');
    }

    public function payment_method()
    {
        return $this->hasOne('App\Models\PaymentMethod', 'id', 'payment_method_id');
    }

    public function products()
    {
        return $this->belongsToMany('App\Models\Product', 'orders_products');
    }
}
