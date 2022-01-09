<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderTable;

class Table extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'guest', 'image'];

    public function ordertables(){
    	return $this->hasMany(OrderTable::class);
    }
    public function orders(){
    	return $this->hasMany(Order::class);
    }
    public function transactions(){
    	return $this->hasMany(Transaction::class);
    }
}
