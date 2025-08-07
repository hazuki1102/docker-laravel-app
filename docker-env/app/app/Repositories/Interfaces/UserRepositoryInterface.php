<?php

namespace App\Repositories\Interfaces;

use App\Models\User;
use App\Models\UserToken;

interface UserRepositoryInterface
{
    /**
     * メールアドレスからユーザー情報を取得
     *
     * @param string $email
     * @return User
     */
    public function findFromMail(string $email): User;

    /**
     * パスワードリセット用トークンを発行
     *
     * @param int $userId
     * @return UserToken
     */
    public function updateOrCreateUser(int $userId): UserToken;

    /**
     * トークンからユーザー情報を取得
     * @param string $token
     * @return UserToken
     */
    public function getUserTokenFromUser(string $token): UserToken;

    /**
     * パスワード更新
     *
     * @param string $password
     * @param int $id
     * @return void
     */
    public function updateUserPassword(string $password, int $id): void;
}
