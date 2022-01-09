<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\Table;
use App\Models\Coupon;
use App\Models\OrderItem;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'table_id', 'subtotal', 'discount', 'tax', 'coupon_id', 'total', 'status', 'note'];

    public function customers(){
    	return $this->belongsTo(Customer::class);
    }
    public function tables(){
    	return $this->belongsTo(Table::class);
    }
    public function coupons(){
    	return $this->belongsTo(Coupon::class);
    }
    public function orderItems(){
        return $this->hasMany(OrderItem::class);
    }
}
