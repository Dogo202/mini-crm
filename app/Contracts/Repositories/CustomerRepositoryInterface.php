<?php
namespace App\Contracts\Repositories;

use App\Models\Customer;

interface CustomerRepositoryInterface
{
    public function findByEmailOrPhone(string $email, string $phone): ?Customer;
    public function firstOrCreate(array $data): Customer;
}
