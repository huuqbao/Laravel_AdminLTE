<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:30',
            'last_name'  => 'required|string|max:20',
            'address'    => 'nullable|string|max:200',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'Vui lòng nhập họ.',
            'last_name.required'  => 'Vui lòng nhập tên.',
            'first_name.max'      => 'Họ không quá 30 ký tự.',
            'last_name.max'       => 'Tên không quá 20 ký tự.',
            'address.max'         => 'Địa chỉ không quá 200 ký tự.',
        ];
    }
}

