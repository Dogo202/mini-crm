<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Resources\TicketResource;
use App\Services\TicketService;

class TicketController extends Controller
{
    // Без readonly — совместимо с IDE на уровне 8.0
    private TicketService $service;

    public function __construct(TicketService $service)
    {
        $this->service = $service;
    }

    public function store(StoreTicketRequest $request)
    {
        $ticket = $this->service->create($request->validated() + [
                'attachments' => $request->file('attachments', [])
            ]);

        return (new TicketResource($ticket))
            ->response()
            ->setStatusCode(201);
    }
}
