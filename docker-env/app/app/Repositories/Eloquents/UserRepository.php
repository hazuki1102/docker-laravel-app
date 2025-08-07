<?php

namespace App\Repositories\Eloquents;

use App\Models\User;
use App\Models\UserToken;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;


class UserRepository implements UserRepositoryInterface
{
    private $user;
    private $userToken;

    /**
     * constructor
     *
     * @param User $user
     */
    public function __construct(User $user, UserToken $userToken)
    {
        $this->user = $user;
        $this->userToken = $userToken;
    }

    // メールアドレスからユーザー情報取得
    public function findFromMail(string $email): User
    {
        return $this->user->where('email', $email)->firstOrFail();
    }

    // パスワードリセット用トークンを発行
    public function updateOrCreateUser(int $userId): UserToken
    {
        $now = Carbon::now();
        $token = hash('sha256', $userId . $now->timestamp);

        $userToken = $this->userToken->updateOrCreate(
            ['user_id' => $userId],
            [
                'rest_password_access_key' => $token,
                'rest_password_expire_data' => $now->addHours(24),
            ]
        );

        \Log::debug(__METHOD__ . ' 作成されたUserToken: ' . print_r($userToken->toArray(), true));

        return $userToken;
    }


    public function getUserTokenFromUser(string $token): UserToken
    {
        return $this->userToken->where('rest_password_access_key', $token)->firstOrFail();
    }

    public function updateUserPassword(string $password, int $id): void
    {
        $this->user->where('id', $id)->update(['password' => $password]);
    }
}
