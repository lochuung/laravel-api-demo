<?php

namespace App\Services;

use App\Http\Resources\AuthResource;
use App\Http\Resources\UserResource;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Contracts\AuthServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

    /**
     * @throws ValidationException
     */
    public function login(array $credentials): AuthResource
    {
        // TODO: Implement login() method.
        $user = $this->userRepository->findByEmail($credentials['email']);
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.']
            ]);
        }
        $token = $user->createToken('auth_token')->accessToken;

        return new AuthResource([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function logout(Request $request): void
    {
        // TODO: Implement logout() method.
        $request->user()->currentAccessToken()->delete();
    }
}
