<?php

namespace App\Services;

use App\Helpers\Http;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    use Http;

    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Generate a new token for a user.
     *
     * @param array $userDetails - Details of the user.
     * @return array - Returns an array with the token or an error message.
     */
    public function generateToken(array $userDetails): array
    {
        $user = $this->repository->getUserByEmail($userDetails['email']);

        if (!empty($user->id)) {
            $checkPass = Hash::check($userDetails['password'], $user->password);

            if (! $checkPass) {
                return $this->unprocessableEntity('Invalid password.');
            }
        } else {
            return $this->notFound("User doesn't exists.");
        }

        $user->tokens()->delete();

        $token = $user->createToken($userDetails['email'])->plainTextToken;

        return $this->ok($token);
    }

    /**
     * Logout the user.
     *
     * @return array - Returns an array with a success message.
     */
    public function logout(): array
    {
        $user = $this->repository->getUserById(auth()->user()->id);

        $user->tokens()->delete();

        return $this->ok('Successfully disconnected.');
    }
}
