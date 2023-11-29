<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MedicineResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' =>(string)$this->id,
            'scientific_name' => $this->scientific_name ,
            'commercial_name' => $this->commercial_name ,
            'max_amount' => $this->max_amount ,
            'total_amount' => $this->total_amount ,
            'price' => $this->price ,
            
            'category' => new CategoryResource($this->category) , 
            'company' => new CompanyResource($this->company) ,

        ];
    }
}