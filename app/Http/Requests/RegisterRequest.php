<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:30',
            'last_name'  => 'required|string|max:30',
            'email'      => 'required|email:rfc,dns|max:100|unique:users,email',
            'password'   => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/',           // Có chữ hoa
                'regex:/[a-z]/',           // Có chữ thường
                'regex:/[0-9]/',           // Có số
                'regex:/[@$!%*#?&]/',      // Có ký tự đặc biệt
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
            'password.min'        => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed'  => 'Xác nhận mật khẩu không khớp.',
            'password.regex'      => 'Mật khẩu phải có chữ hoa, chữ thường, số và ký tự đặc biệt.',
        ];
    }
}
