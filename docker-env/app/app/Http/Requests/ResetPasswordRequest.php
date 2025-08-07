<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\TokenExpirationTimeCheck;

class ResetPasswordRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'password' => ['required', 'regex:/^[0-9a-zA-Z\-_]{8,32}$/', 'confirmed'],
            'password_confirmation' => ['required'],
            'reset_token' => ['required', new TokenExpirationTimeCheck()],
        ];
    }

    public function messages()
    {
        return [
            'password.required' => 'パスワードを入力してください',
            'password.regex' => 'パスワードは半角英数字とハイフンとアンダーバーのみで8文字以上32文字以内で入力してください',
            'password.confirmed' => 'パスワードが再入力欄と一致していません',
        ];
    }
}
