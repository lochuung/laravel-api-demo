<?php

namespace App\Services;

use App\Http\Resources\Auth\AuthResource;
use App\Http\Resources\Auth\RefreshTokenResource;
use App\Http\Resources\Users\UserResource;
use App\Notifications\ResetPasswordNotification;
use App\Notifications\VerifyEmailNotification;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Contracts\AuthServiceInterface;
use App\Utilities\Psr7Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Symfony\Component\HttpFoundation\Response;

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

        // Generate email verification token and send email
        $userWithToken = $this->userRepository->generateEmailVerificationToken($user);
        $userWithToken->notify(
            new VerifyEmailNotification($userWithToken->email_verification_token, $userWithToken->email)
        );

        return new UserResource($userWithToken);
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

        if (!$user->is_active) {
            throw ValidationException::withMessages([
                'email' => ['Your account is inactive. Please contact support.']
            ]);
        }

        if (!$user->email_verified) {
            throw ValidationException::withMessages([
                'email' => ['Please verify your email address before logging in.']
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

        if ($response->getStatusCode() !== Response::HTTP_OK) {
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

    /**
     * @throws ValidationException
     */
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
        if ($response->getStatusCode() !== Response::HTTP_OK) {
            throw ValidationException::withMessages([
                'refresh_token' => ['The refresh token is invalid or expired.']
            ]);
        }
        $data = json_decode((string)$response->getContent(), true);
        return new RefreshTokenResource($data);
    }


    /**
     * @throws ValidationException
     */
    public function verifyEmail(array $data): UserResource
    {
        $user = $this->userRepository->findByEmailVerificationToken($data['token']);

        if (!$user || $user->email !== $data['email']) {
            throw ValidationException::withMessages([
                'token' => ['The verification token is invalid.']
            ]);
        }

        if ($user->email_verification_token_expires_at < now()) {
            throw ValidationException::withMessages([
                'token' => ['The verification token has expired.']
            ]);
        }

        if ($user->email_verified) {
            throw ValidationException::withMessages([
                'email' => ['Email is already verified.']
            ]);
        }

        $verifiedUser = $this->userRepository->updateEmailVerificationStatus($user, true);
        return new UserResource($verifiedUser);
    }


    /**
     * @throws ValidationException
     */
    public function resendVerificationEmail(array $data): bool
    {
        $user = $this->userRepository->findByEmail($data['email']);

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['User not found.']
            ]);
        }

        if ($user->email_verified) {
            throw ValidationException::withMessages([
                'email' => ['Email is already verified.']
            ]);
        }

        $userWithToken = $this->userRepository->generateEmailVerificationToken($user);
        $userWithToken->notify(
            new VerifyEmailNotification($userWithToken->email_verification_token, $userWithToken->email)
        );

        return true;
    }


    /**
     * @throws ValidationException
     */
    public function forgotPassword(array $data): bool
    {
        $user = $this->userRepository->findByEmail($data['email']);

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['We can\'t find a user with that email address.']
            ]);
        }

        $token = Password::broker()->createToken($user);
        $user->notify(new ResetPasswordNotification($token, $user->email));
        return true;
    }

    /**
     * @throws ValidationException
     */
    public function resetPassword(array $data): UserResource
    {
        $status = Password::reset(
            $data,
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            $user = $this->userRepository->findByEmail($data['email']);
            return new UserResource($user);
        }

        throw ValidationException::withMessages([
            'email' => [__($status)]
        ]);
    }
}
