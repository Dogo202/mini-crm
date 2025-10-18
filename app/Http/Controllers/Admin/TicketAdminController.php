<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateTicketStatusRequest;
use App\Contracts\Repositories\TicketRepositoryInterface;
use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TicketAdminController extends Controller
{
    private TicketRepositoryInterface $tickets;

    public function __construct(TicketRepositoryInterface $tickets)
    {
        $this->middleware(['auth','role:manager']);
        $this->tickets = $tickets;
    }

    public function index(): View
    {
        $page = $this->tickets->paginated(request()->only('status','email','phone','from','to'));
        return view('admin.tickets.index', ['tickets' => $page]);
    }

    public function show(Ticket $ticket): View
    {
        $ticket->load('customer','media');
        return view('admin.tickets.show', compact('ticket'));
    }

    public function updateStatus(UpdateTicketStatusRequest $request, Ticket $ticket): RedirectResponse
    {
        $this->tickets->updateStatus($ticket, $request->validated()['status']);
        return back()->with('success', 'Статус обновлён');
    }
}
