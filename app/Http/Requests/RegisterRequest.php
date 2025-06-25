<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:30', //required thêm *
            'last_name'  => 'required|string|max:30',
            'email'      => 'required|email:rfc,dns|max:100|unique:users,email',//email từ >= 3 rules tro len phai dung mang k dc dung chuoi
            'password'   => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()    // Chữ hoa + thường
                    ->numbers()      // Có số
                    ->symbols(),     // Có ký tự đặc biệt
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'Vui lòng nhập tên.',
            'first_name.max'      => 'Tên không được vượt quá 30 ký tự.',

            'last_name.required'  => 'Vui lòng nhập họ.',
            'last_name.max'       => 'Họ không được vượt quá 30 ký tự.',

            'email.required'      => 'Vui lòng nhập email.',
            'email.email'         => 'Email không đúng định dạng.',
            'email.max'           => 'Email không được vượt quá 100 ký tự.',
            'email.unique'        => 'Email đã tồn tại.',

            'password.required'   => 'Vui lòng nhập mật khẩu.',
            'password.confirmed'  => 'Xác nhận mật khẩu không khớp.',
            'password.min'        => 'Mật khẩu phải có ít nhất :min ký tự.',
            'password.mixed'      => 'Mật khẩu phải chứa chữ hoa và chữ thường.',
            'password.numbers'    => 'Mật khẩu phải chứa ít nhất một số.',
            'password.symbols'    => 'Mật khẩu phải chứa ít nhất một ký tự đặc biệt.',
        ];
    }
}
