<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\TicketRepositoryInterface;
use App\Http\Controllers\Controller;

class TicketStatisticsController extends Controller
{
    private TicketRepositoryInterface $tickets;

    public function __construct(TicketRepositoryInterface $tickets)
    {
        $this->tickets = $tickets;
    }

    public function __invoke()
    {
        return response()->json($this->tickets->statistics());
    }
}
