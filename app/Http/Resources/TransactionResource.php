<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Customer;
use App\Models\Table;
use App\Models\Order;
use App\Models\User;
use App\Models\Coupon;

class TransactionResource extends JsonResource
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
        $order = Order::where('id', $this->id)->get();
        $table = Table::where('id', $this->table_id)->first();
        $user = User::where('id', $this->user_id)->first();
        $coupon = Coupon::where('id', $order->coupon_id)->first();

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
            'user' => $user->name,
            'customer' => $customer->name,
            'table' => $t_name,
            'order' => [
                'subtotal' => $order->subtotal,
                'discount' => $order->discount,
                'tax' => $order->tax,
                'coupon_id' => [
                    'code' => $c_code,
                    'discount' => $c_discount,
                ],
                'grandtotal' => $order->total,
            ],
            'type' => $this->type,
            'mode' => $this->mode,
            'cash' => $this->cash,
            'balance' => $this->balance,
            'status' => $this->status,
            'note' => $this->note
        ];
    }
}
