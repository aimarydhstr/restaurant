<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Product;
use App\Models\Order;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $product = Product::where('id', $this->product_id)->first();
        $order = Order::where('id', $this->order_id)->first();

        return [
            'id' => $this->id,
            'product' => [
                'name' => $product->name,
                'price' => $product->price,
                'discount' => $product->discount,
            ],
            'type' => $this->type,
            'discount' => $this->discount,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'order' => [
                'subtotal' => $order->subtotal,
                'discount' => $order->discount,
                'tax' => $order->tax,
                'total' => $order->total,
            ],
            'note' => $this->note
        ];   
    }
}
