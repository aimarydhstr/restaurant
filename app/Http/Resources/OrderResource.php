<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Customer;
use App\Models\Table;
use App\Models\Coupon;
use App\Models\OrderItem;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $customer = Customer::where('id', $this->customer_id)->first();
        $orderItem = OrderItem::where('id', $this->id)->get();
        $table = Table::where('id', $this->table_id)->first();
        $coupon = Coupon::where('id', $this->coupon_id)->first();

        $t_name = NULL;
        if (!empty($table)) {
            $t_name = $table->name;
        }

        $c_code = NULL;
        $c_discount = NULL;
        if (!empty($coupon)) {
            $c_code = $coupon->code;
            $c_discount = $coupon->discount;
        }

        return [
            'id' => $this->id,
            'customer' => $customer->name,
            'table' => $t_name,
            'item' => $orderItem,
            'subtotal' => $this->subtotal,
            'discount' => $this->discount,
            'tax' => $this->tax,
            'coupon_id' => [
                'code' => $c_code,
                'discount' => $c_discount,
            ],
            'total' => $this->total,
            'status' => $this->status,
            'note' => $this->note
        ];   
    }
}
