<?php

namespace App\Services;

use App\Http\Resources\AuthResource;
use App\Http\Resources\UserResource;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Contracts\AuthServiceInterface;
use Auth;
use Illuminate\Validation\ValidationException;

class AuthService implements AuthServiceInterface
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @throws ValidationException
     */
    public function register(array $data): UserResource
    {
        // TODO: Implement register() method.
        // Kiểm tra email đã tồn tại bằng method mới
        if ($this->userRepository->emailExists($data['email'])) {
            throw ValidationException::withMessages([
                'email' => ['The email has already been taken.']
            ]);
        }
        $user = $this->userRepository->createWithHashedPassword($data);
        return new UserResource($user);
    }

    public function login(array $credentials): AuthResource
    {
        // TODO: Implement login() method.
        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.']
            ]);
        }
        $user = $this->userRepository->findByEmail($credentials['email']);
        $token = $user->createToken('auth_token')->plainTextToken;
        return new AuthResource([
            'user' => new UserResource($user),
            'token' => $token,
        ]);
    }

    public function logout(): void
    {
        // TODO: Implement logout() method.
        $user = Auth::user();
        $user?->tokens()->delete();
        Auth::logout();
    }
}
