<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\OrderTable;

class TableResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $ordertable = OrderTable::where('table_id', $this->id)->first();

        if (!empty($ordertable)) {
            $status = $ordertable->status;
            if ($status == 1) {
                $status = 'hold on';
            } elseif($status == 2) {
                $status = 'occupied';
            } elseif ($status == 0) {
                $status = 'avalaible';
            }
        } else {
            $status = 'avalaible';
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'guest' => $this->guest,
            'image' => $this->image,
            'status' => $status
        ];  
    }
}
