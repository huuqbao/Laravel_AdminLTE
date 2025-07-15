<?php

namespace App\Http\Requests;

use App\Enums\PostStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use App\Models\Post;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rule;
/**
 * @property-read Post|null $post
 * @method bool hasFile(string $key)
 * @method UploadedFile|null file(string $key)
 * @method \Illuminate\Routing\Route route(string|null $key = null, mixed $default = null)
 */
class PostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isUpdate = $this->route('post') !== null;

        return [
            'title' => 'required|string|max:100',
            'slug' => 'nullable|string|max:300',
            'description' => 'required|string|max:300',
            'content' => 'required|string',
            'publish_date' => 'required|date',
            'status' => [
                'nullable',
                Rule::in([
                    PostStatus::NEW->value,
                    PostStatus::UPDATED->value,
                    PostStatus::PUBLISHED->value,
                ]),
            ],
            'thumbnail' => [
                'required',
                'image',
                'mimes:jpeg,png,jpg,gif,svg',
                'max:2048',
            ],
        ];
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
        ];
    }
}