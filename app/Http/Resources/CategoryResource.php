<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'is_active' => $this->isActive ?? $this->is_active,
            'created_at' => Carbon::make($this->createdAt ?? $this->created_at)->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::make($this->updatedAt ?? $this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
