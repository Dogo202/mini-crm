<?php
namespace App\Contracts\Repositories;

use App\Models\Ticket;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TicketRepositoryInterface
{
    public function create(array $data): Ticket;
    public function find(int $id): ?Ticket;
    public function paginated(array $filters): LengthAwarePaginator;
    public function existsRecentForEmailOrPhone(string $email, string $phone, int $hours = 24): bool;
    public function statistics(): array;
    public function updateStatus(Ticket $ticket, string $status): Ticket;
}
