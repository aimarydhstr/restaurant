<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Table;
use App\Models\Customer;

class OrderTable extends Model
{
    use HasFactory;

    protected $fillable = ['table_id', 'customer_id', 'status'];

    public function tables(){
    	return $this->belongsTo(Table::class);
    }
    public function customers(){
    	return $this->belongsTo(Customer::class);
    }
}
