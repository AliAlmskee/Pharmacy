<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            "id"=> (string)$this->id,
            "attributes" => [
                'name' => $this->name,  
                'status' => $this->status,    
                'date'=> $this->date,
                'paid' => $this->paid,
                'total_price' => $this->total_price,
                'created_at' => $this->created_at,
                'updated_at'=> $this->updated_at,
            ],
            "relationships" => [
                'pharmacist' => $this->pharmacist,
                'medicines' => MedicineResource::collection($this->medicines)
            ]
        ];
    }
}
