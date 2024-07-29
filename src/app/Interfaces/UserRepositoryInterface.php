<?php

namespace App\Interfaces;

use Illuminate\Pagination\Paginator;

interface UserRepositoryInterface
{
    public function createUser(array $userDetails);
    public function getUserById(int $userId);
    public function getUsers(): Paginator;
    public function updateUser(int $userId, array $userDetails);
    public function deleteUser(int $userId);
}
