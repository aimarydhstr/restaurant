<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Table;
use App\Models\Customer;

class OrderTableResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $table = Table::where('id', $this->table_id)->first();
        $customer = Customer::where('id', $this->customer_id)->first();

        return [
            'id' => $this->id,
            'table' => $table->name,
            'customer' => $customer->name,
            'status' => $this->status
        ];    
    }
}
