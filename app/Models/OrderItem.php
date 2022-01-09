<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\Order;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'order_id', 'type', 'discount', 'quantity', 'price'];

    public function products(){
    	return $this->belongsTo(Product::class);
    }
    public function orders(){
    	return $this->belongsTo(Order::class);
    }
}