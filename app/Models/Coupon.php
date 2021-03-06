<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'discount', 'status'];

    public function orders(){
    	return $this->hasMany(Order::class);
    }
}
