<?php

namespace App\Services;

use App\Helpers\Http;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserService
{
    use Http;

    private UserRepository $repository;

    public function __construct()
    {
        $this->repository = new UserRepository;
    }

    /**
     * Create a new user.
     *
     * @param array $userDetails - Details of the user.
     * @return array - Returns an array with the created user or an error message.
     */
    public function createUser(array $userDetails): array
    {
        $userDetails['password'] = Hash::make($userDetails['password']);

        $user = $this->repository->createUser($userDetails);

        return $this->created($user);
    }

    /**
     * Get a specific user.
     *
     * @param int $userId - The ID of the user.
     * @return array - Returns an array with the user or an error message.
     */
    public function getUser(int $userId): array
    {
        $error = $this->checkIfHasError($userId);

        if (! empty($error)) {
            return $error;
        }

        $user = $this->repository->getUserById($userId);

        return $this->ok($user);
    }

    /**
     * Get all users.
     *
     * @return array - Returns an array with all users.
     */
    public function getUsers(): array
    {
        $users = $this->repository->getUsers();

        return $this->ok($users->items());
    }

    /**
     * Check if there are any errors.
     *
     * @param int $userId - The ID of the user.
     * @param bool $checkPermission - Whether to check for permissions.
     * @return array - Returns an array with an error message if there are any errors.
     */
    private function checkIfHasError(int $userId, bool $checkPermission = false): array
    {
        if (! $this->userExists($userId)) {
            return $this->notFound("User doesn't exists.");
        }

        if ($checkPermission && $userId !== auth()->user()->id) {
            return $this->forbidden("You don't have permission to update or delete this user.");
        }

        return [];
    }

    /**
     * Check if a user exists.
     *
     * @param int $userId - The ID of the user.
     * @return bool - Returns true if the user exists, false otherwise.
     */
    private function userExists(int $userId): bool
    {
        $user = $this->repository->getUserById($userId);

        if (empty($user->id)) {
            return false;
        }

        return true;
    }

    /**
     * Update a specific user.
     *
     * @param int $userId - The ID of the user.
     * @param array $userDetails - The new details of the user.
     * @return array - Returns an array with the updated user or an error message.
     */
    public function updateUser(int $userId, array $userDetails): array
    {
        $error = $this->checkIfHasError($userId, true);

        if (! empty($error)) {
            return $error;
        }

        $this->repository->updateUser($userId, $userDetails);

        $user = $this->repository->getUserById($userId);

        return $this->ok($user);
    }

    /**
     * Delete a specific user.
     *
     * @param int $userId - The ID of the user.
     * @return array - Returns an array with a success message or an error message.
     */
    public function deleteUser(int $userId): array
    {
        $error = $this->checkIfHasError($userId, true);

        if (! empty($error)) {
            return $error;
        }

        $this->repository->deleteUser($userId);

        return $this->ok('User successfully deleted!');
    }
}
