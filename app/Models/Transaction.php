<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Table;
use App\Models\User;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['user_id' ,'customer_id', 'order_id', 'table_id', 'type', 'mode', 'total', 'cash', 'balance', 'status', 'note'];

    public function orders(){
    	return $this->belongsTo(Order::class);
    }
    public function customers(){
    	return $this->belongsTo(Customer::class);
    }
    public function tables(){
    	return $this->belongsTo(Table::class);
    }
    public function users(){
    	return $this->belongsTo(User::class);
    }
}
