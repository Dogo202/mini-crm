<?php
namespace App\Repositories;

use App\Contracts\Repositories\TicketRepositoryInterface;
use App\Models\Ticket;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentTicketRepository implements TicketRepositoryInterface
{
    public function create(array $data): Ticket
    {
        return Ticket::create($data);
    }

    public function find(int $id): ?Ticket
    {
        return Ticket::with('customer','media')->find($id);
    }

    public function paginated(array $filters): LengthAwarePaginator
    {
        return Ticket::query()
            ->with('customer')
            ->when($filters['status'] ?? null, fn($q,$v) => $q->where('status',$v))
            ->when($filters['email'] ?? null, fn($q,$v) => $q->whereHas('customer', fn($c)=>$c->where('email','like',"%$v%")))
            ->when($filters['phone'] ?? null, fn($q,$v) => $q->whereHas('customer', fn($c)=>$c->where('phone','like',"%$v%")))
            ->when(($filters['from'] ?? null) && ($filters['to'] ?? null),
                fn($q) => $q->whereBetween('created_at', [$filters['from'], $filters['to']]))
            ->latest()
            ->paginate(15)->withQueryString();
    }

    public function existsRecentForEmailOrPhone(string $email, string $phone, int $hours = 24): bool
    {
        return Ticket::whereHas('customer', fn($c) => $c->where('email',$email)->orWhere('phone',$phone))
            ->where('created_at', '>=', now()->subHours($hours))
            ->exists();
    }

    public function statistics(): array
    {
        return [
            'today' => Ticket::today()->count(),
            'week'  => Ticket::thisWeek()->count(),
            'month' => Ticket::thisMonth()->count(),
        ];
    }

    public function updateStatus(Ticket $ticket, string $status): Ticket
    {
        $ticket->status = $status;
        if (in_array($status, ['in_progress','resolved'], true)) {
            $ticket->manager_replied_at = now();
        }
        $ticket->save();
        return $ticket;
    }
}
