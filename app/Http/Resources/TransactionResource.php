<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => $this->type == 'increase' ? 'افزایش' : 'کاهش',
            'status' => $this->status ? 'موفقیت آمیز' : 'ناموفق',
            'value' => $this->value,
            'description' => $this->description,
            'code' => $this->code,
            'date'=>$this->created_at
        ];
    }
}
