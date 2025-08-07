<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Http\Requests\ResetInputMailRequest;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Exception;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Support\Facades\Hash;

//これはパスワードリセット用のコントローラー//
class UsersController extends Controller
{
    private $userRepository;
    private const MAIL_SENDED_SESSION_KEY = 'user_reset_password_mail_sended_action';

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function requestResetPassword()
    {
        return view('Auth.reset_input_mail');
    }

    //  メール送信
    public function sendResetPasswordMail(ResetInputMailRequest $request)
    {
        try {
            // ユーザー情報取得
            $user = $this->userRepository->findFromMail($request->email);
            $userToken = $this->userRepository->updateOrCreateUser($user->id);

            // メール送信
            Log::info(__METHOD__ . '...ID:' . $user->id . 'のユーザーにパスワード再設定用メールを送信します。');
            Mail::send(new ResetPasswordMail($user, $userToken));
            Log::info(__METHOD__ . '...ID:' . $user->id . 'のユーザーにパスワード再設定用メールを送信しました。');
        } catch(Exception $e) {
            Log::error(__METHOD__ . '...ユーザーへのパスワード再設定用メール送信に失敗しました。 request_email = ' . $request->email . ' error_message = ' . $e);
            return redirect()->route('reset.form')
                ->with('flash_message', '処理に失敗しました。時間をおいて再度お試しください。');
        }
        // 不正アクセス防止セッションキー
        session()->put(self::MAIL_SENDED_SESSION_KEY, 'user_reset_password_send_email');
        /* ③ここまで追加 */
        return redirect()->route('reset.send.complete');
    }

    // メール送信完了
    public function sendCompleteResetPasswordMail()
    {
        /* ④ここから追加 */
        // 不正アクセス防止セッションキーを持っていない場合
        if (session()->pull(self::MAIL_SENDED_SESSION_KEY) !== 'user_reset_password_send_email') {
            return redirect()->route('reset.form')
                ->with('flash_message', '不正なリクエストです。');
        }
        /* ④ここまで追加 */
        return view('auth.reset_input_mail_complete');
    }

    // パスワード再設定
            public function resetPassword(Request $request)
            {
                if (!$request->hasValidSignature()) {
                    abort(403, 'URLの有効期限が過ぎたためエラーが発生しました。');
                }

                $resetToken = $request->reset_token;

                try {
                    $userToken = $this->userRepository->getUserTokenFromUser($resetToken);

                    // ★ここを追加してログに出す
                    \Log::debug(__METHOD__ . ' resetPassword() $userToken: ' . print_r($userToken->toArray(), true));

                } catch (Exception $e) {
                    Log::error(__METHOD__ . ' UserTokenの取得に失敗しました。 error_message = ' . $e);
                    return redirect()->route('reset.form')->with('flash_message', 'メールのURLから遷移してください。');
                }

                return view('auth.reset_input_password', compact('userToken'));
            }


    // パスワード更新
    public function updatePassword(ResetPasswordRequest $request)
    {
        \Log::debug('🚨 updatePassword() メソッドが呼び出されました');
        try {
            $userToken = $this->userRepository->getUserTokenFromUser($request->reset_token);
            \Log::debug(__METHOD__ . ' $userToken->toArray(): ' . json_encode($userToken->toArray()));
            \Log::debug(__METHOD__ . ' $userToken->user_id: ' . $userToken->user_id);

            // ✅ パスワードをHashで保存（←ログイン時にも有効）
            $password = Hash::make($request->password);

            $this->userRepository->updateUserPassword($password, $userToken->user_id);

            Log::info(__METHOD__ . '...ID:' . $userToken->user_id . 'のユーザーのパスワードを更新しました。');
        } catch (Exception $e) {
            Log::error(__METHOD__ . '...ユーザーのパスワードの更新に失敗しました。...error_message = ' . $e);
            return redirect()->route('reset.form')
                ->with('flash_message', __('処理に失敗しました。時間をおいて再度お試しください。'));
        }

        return view('auth.reset_input_password_complete');
    }

}

