<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'DT_RowIndex' => $this->resource->row_index ?? null, // sẽ gán ở controller/service
            'name' => $this->name,
            'email' => $this->email,
            'address' => $this->address ?? 'N/A',
            'status' => "<span class='{$this->status_class}'>{$this->status_label}</span>",
            'id' => $this->id,
        ];
    }
}
