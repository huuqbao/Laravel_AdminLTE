<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'DT_RowIndex'   => $this->index, 
            'thumbnail'     => $this->thumbnail,
            'title'         => Str::limit($this->title, 50),
            'description'   => Str::limit($this->description, 80),
            'publish_date' => format_datetime_local($this->publish_date),
            'status'        => $this->status_badge,
            'id'            => $this->id,
        ];
    }
}
