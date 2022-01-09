<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Category;
use App\Models\Tag;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'slug', 'summary', 'category_id', 'tag_id', 'image', 'price', 'discount', 'status'];

    public function users(){
    	return $this->belongsTo(User::class);
    }
    public function categories(){
    	return $this->belongsTo(Category::class);
    }
    public function tags(){
    	return $this->belongsTo(Tag::class);
    }
}
