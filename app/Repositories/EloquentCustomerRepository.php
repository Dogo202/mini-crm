<?php
namespace App\Repositories;

use App\Contracts\Repositories\CustomerRepositoryInterface;
use App\Models\Customer;

class EloquentCustomerRepository implements CustomerRepositoryInterface
{
    public function findByEmailOrPhone(string $email, string $phone): ?Customer
    {
        return Customer::where('email', $email)
            ->orWhere('phone', $phone)
            ->first();
    }

    public function firstOrCreate(array $data): Customer
    {
        return Customer::firstOrCreate(
            ['email' => $data['email'], 'phone' => $data['phone']],
            ['name'  => $data['name'] ?? '']
        );
    }
}
