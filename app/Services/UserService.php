<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Traits\Exceptionable;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserService
{
    private ?User $user;

    use Exceptionable;

    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function register(array $data): array
    {
        try {
            $data['password'] = Hash::make($data['password']);
            $result = $this->userRepository->create($data)->toArray();
            if (!$result) {
                throw new Exception(message: __('user.register.exception'));
            }
            return [
                'status' => Response::HTTP_CREATED,
                'data' => $result,
                'message' => __('user.register.succeed')
            ];
        } catch (Exception $exception) {
            return $this->except($exception);
        }
    }

    public function login(array $data): array
    {
        try {
            $user = $this->userRepository->findBY('email', $data['email']);
            if (!$user || Hash::check($data['password'], $user->password)) {
                throw new Exception(message: __('user.credential_error'), code: 400);
            }
            return [
                'status' => Response::HTTP_OK,
                'data' => [
                    'token' => $user->createToken($data['email'])->plainTextToken
                ]
            ];
        } catch (Exception $exception) {
            return $this->except($exception);
        }
    }

    public function logout(): array
    {
        try {
            $this->user->tokens()->delete();
            return [
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => __('user.logout')
            ];
        } catch (Exception $exception) {
            return $this->except($exception);
        }
    }
}
