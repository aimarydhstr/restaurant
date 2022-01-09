<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;
use App\Models\Category;
use App\Models\Tag;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $user = User::where('id', $this->user_id)->first();
        $category = Category::where('id', $this->category_id)->first();
        $tag = Tag::where('id', $this->tag_id)->first();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'user' => $user->firstname.' '.$user->lastname,
            'slug' => $this->slug,
            'summary' => $this->summary,
            'category' => $category->name,
            'tag' => $tag->name,
            'image' => $this->image,
            'price' => $this->price,
            'discount' => $this->discount,
            'status' => $this->status,
        ];
    }
}
