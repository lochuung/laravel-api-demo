<?php

namespace App\Services;

use App\Http\Resources\AuthResource;
use App\Http\Resources\RefreshTokenResource;
use App\Http\Resources\UserResource;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Contracts\AuthServiceInterface;
use App\Utilities\Psr7Util;
use GuzzleHttp\Psr7\ServerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;

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

        $params = [
            ...config('passport-params'),
            'grant_type' => 'password',
            'username' => $credentials['email'],
            'password' => $credentials['password'],
        ];

        $requestPs7 = Psr7Util::createPsr7Request(
            Request::create('/oauth/token', 'POST', $params)
        );

        $responsePsr7 = Psr7Util::createPsr7Response();
        $response = app(AccessTokenController::class)->issueToken(
            $requestPs7,
            $responsePsr7
        );

        if ($response->getStatusCode() !== 200) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.']
            ]);
        }
        $data = json_decode((string)$response->getContent(), true);

        return new AuthResource([
            'user' => $user,
            ...$data
        ]);
    }

    public function logout(Request $request): void
    {
        // TODO: Implement logout() method.
        $request->user()->currentAccessToken()->delete();
    }

    public function refresh(array $data): RefreshTokenResource
    {
        // TODO: Implement refresh() method.
        $refreshToken = $data['refresh_token'] ?? null;
        if (!$refreshToken) {
            throw ValidationException::withMessages([
                'refresh_token' => ['The refresh token is required.']
            ]);
        }
        $params = [
            ...config('passport-params'),
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
        ];
        $requestPs7 = Psr7Util::createPsr7Request(
            Request::create('/oauth/token', 'POST', $params)
        );
        $responsePsr7 = Psr7Util::createPsr7Response();
        $response = app(AccessTokenController::class)->issueToken(
            $requestPs7,
            $responsePsr7
        );
        if ($response->getStatusCode() !== 200) {
            throw ValidationException::withMessages([
                'refresh_token' => ['The refresh token is invalid or expired.']
            ]);
        }
        $data = json_decode((string)$response->getContent(), true);
        return new RefreshTokenResource($data);
    }
}
