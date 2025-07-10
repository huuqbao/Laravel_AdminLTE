<?php

namespace App\Http\Requests\PostRequest;

use App\Enums\PostStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Enums\RoleStatus;


class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::user()?->can('create', \App\Models\Post::class);
    }

    public function rules(): array
    {
        $rules = [
            'title' => 'required|string|max:100',
            'slug' => 'nullable|string|max:300',
            'description' => 'required|string|max:300',
            'content' => 'required|string',
            'publish_date' => 'required|date',
            'thumbnail' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,svg',
                'max:2048',
            ],
        ];

        // Nếu là admin thì mới cho phép submit status
        if (Auth::check() && Auth::user()?->role === RoleStatus::ADMIN->value) {
            $rules['status'] = [
                'required',
                Rule::in([
                    PostStatus::NEW->value,
                    PostStatus::UPDATED->value,
                    PostStatus::PUBLISHED->value,
                ]),
            ];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Vui lòng nhập tiêu đề.',
            'title.max' => 'Tiêu đề tối đa 300 ký tự.',
            'description.required' => 'Vui lòng nhập mô tả.',
            'description.max' => 'Mô tả tối đa 500 ký tự.',
            'content.required' => 'Vui lòng nhập nội dung.',
            'publish_date.required' => 'Vui lòng chọn ngày đăng.',
            'thumbnail.required' => 'Vui lòng chọn ảnh.',
            'thumbnail.image' => 'Tệp phải là ảnh.',
            'thumbnail.mimes' => 'Chỉ chấp nhận: jpeg, png, jpg, gif, svg.',
            'status.enum' => 'Trạng thái không hợp lệ.',
            'status.required' => 'Vui lòng chọn trạng thái.',
        ];
    }
}
