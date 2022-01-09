<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderTable;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'phone', 'email', 'image', 'address', 'country', 'state', 'city', 'pincode'];

    public function ordertables(){
    	return $this->hasMany(OrderTable::class);
    }
}
