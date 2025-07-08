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

    public function rules(): array
    {
        $isUpdate = $this->route('post') !== null;

        return [
            'title' => 'required|string|max:300',
            'slug' => 'nullable|string|max:300',
            'description' => 'nullable|string|max:300',
            'content' => 'required|string',
            'publish_date' => 'nullable|date',
            'status' => 'nullable|in:0,1,2',
            'thumbnail' => [
                $isUpdate ? 'nullable' : 'required',
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
            'description.max' => 'Mô tả tối đa 300 ký tự.',
            'content.required' => 'Vui lòng nhập nội dung.',
            'thumbnail.required' => 'Vui lòng chọn ảnh.',
            'thumbnail.image' => 'Tệp phải là ảnh.',
            'thumbnail.mimes' => 'Chỉ chấp nhận: jpeg, png, jpg, gif, svg.',
        ];
    }
}