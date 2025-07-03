<?php

namespace App\Http\Requests;

use App\Enums\PostStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use App\Models\Post;

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

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'content' => 'required|string',
            'publish_date' => 'required|date',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Vui lòng nhập tiêu đề',
            'description.required' => 'Vui lòng nhập mô tả',
            'content.required' => 'Vui lòng nhập nội dung',
            'publish_date.required' => 'Vui lòng nhập ngày đăng',
            'thumbnail.required' => 'Vui lòng chọn ảnh',
            'thumbnail.image' => 'Tệp tải lên phải là hình ảnh',
            'thumbnail.mimes' => 'Ảnh phải có định dạng jpeg, png, jpg, gif hoặc svg',
        ];
    }
}