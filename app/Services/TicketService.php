<?php
namespace App\Services;

use App\Contracts\Repositories\CustomerRepositoryInterface;
use App\Contracts\Repositories\TicketRepositoryInterface;
use App\Exceptions\DomainException;
use App\Models\Ticket;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class TicketService
{
    public function __construct(
        private readonly CustomerRepositoryInterface $customers,
        private readonly TicketRepositoryInterface $tickets
    ) {}

    /**
     * @param array{name:string,email:string,phone:string,subject:string,message:string,attachments?:UploadedFile[]} $payload
     */
    public function create(array $payload): Ticket
    {
        // Ограничение: 1 заявка за 24 часа на email/телефон
        if ($this->tickets->existsRecentForEmailOrPhone($payload['email'], $payload['phone'], 24)) {
            throw new \App\Exceptions\DomainException('Вы уже отправляли заявку в последние 24 часа.', 429);
        }

        return DB::transaction(function () use ($payload) {
            $customer = $this->customers->firstOrCreate([
                'name'  => $payload['name'],
                'email' => $payload['email'],
                'phone' => $payload['phone'],
            ]);

            $ticket = $this->tickets->create([
                'customer_id' => $customer->id,
                'subject'     => $payload['subject'],
                'message'     => $payload['message'],
            ]);

            /** @var UploadedFile[]|null $files */
            $files = $payload['attachments'] ?? null;
            if ($files) {
                foreach ($files as $file) {
                    $ticket->addMedia($file)->toMediaCollection('attachments');
                }
            }

            return $ticket->load('customer','media');
        });
    }
}
