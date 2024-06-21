<?php

namespace App\Services;

use App\Enums\UserActivationStatus;
use App\Exceptions\EmailException;
use App\Exceptions\user\ActivationException;
use App\Exceptions\user\CredentialException;
use App\Exceptions\user\RegistrationException;
use App\Exceptions\user\UserActivationException;
use App\Mail\ActivationMail;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Traits\Exceptionable;
use App\Traits\HashIdConverter;
use Exception;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;

class UserService
{
    private ?User $user;

    use Exceptionable, HashIdConverter;

    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function register(array $data): array
    {
        try {
            $data['password'] = Hash::make($data['password']);
            $user = $this->userRepository->create($data)->toArray();
            if (!$user) {
                throw new RegistrationException();
            }
            $emailSentStatus = $this->sendMail(new ActivationMail($user['id']), $user['email']);
            if (!$emailSentStatus) {
                throw new EmailException();
            }
            Cache::set('activation_pending_' . $user['id'], 1, 86400);
            return [
                'status' => Response::HTTP_CREATED,
                'data' => $user,
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
                throw new CredentialException();
            }
            if ($user->activation_status != UserActivationStatus::ACTIVE->value) {
                throw new ActivationException();
            }
            return [
                'status' => Response::HTTP_OK,
                'data' => [
                    'token' => $user->createToken($data['email'])->plainTextToken
                ],
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
                'message' => __('user.logout'),
            ];
        } catch (Exception $exception) {
            return $this->except($exception);
        }
    }

    public function activateProfile(string $hashedId): array
    {
        try {
            if (!Cache::has('activation_pending_' . $hashedId)) {
                throw new UserActivationException(__('user.activation.expired'));
            }
            $id = $this->deHash($hashedId, 'user');
            $userUpdateStatus = $this->userRepository->update($id, [
                'activation_status' => UserActivationStatus::ACTIVE->value,
                'email_verified_at' => date('Y-m-d H:i:s')
            ]);
            if (!$userUpdateStatus) {
                throw new UserActivationException();
            }
            return [
                'status' => Response::HTTP_OK,
                'message' => __('user.activation.succeed'),
            ];
        } catch (Exception $exception) {
            return $this->except($exception);
        }
    }

    private function sendMail(Mailable $mailable, string $email): bool
    {
        try {
            Mail::to($email)
                ->send($mailable);
            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

}
