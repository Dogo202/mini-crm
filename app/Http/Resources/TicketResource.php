<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Ticket */
class TicketResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'subject'    => $this->subject,
            'message'    => $this->message,
            'status'     => $this->status,
            'created_at' => $this->created_at,
            'customer'   => [
                'id'    => $this->customer->id,
                'name'  => $this->customer->name,
                'email' => $this->customer->email,
                'phone' => $this->customer->phone,
            ],
            'attachments' => $this->getMedia('attachments')->map(fn($m) => [
                'id'   => $m->id,
                'name' => $m->file_name,
                'size' => $m->size,
                'url'  => $m->getUrl(),
            ]),
        ];
    }
}
