<?php

namespace App\Http\Requests;

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
        $slugRule = 'nullable|string|max:100|unique:posts,slug';

        if ($this->route('post')) {
            $slugRule .= ',' . $this->route('post')->id;
        }

        return [
            'title' => 'required|string|max:100',
            'slug' => $slugRule,
            'description' => 'nullable|string|max:200',
            'content' => 'required|string',
            'publish_date' => 'nullable|date',
            'thumbnail' => 'nullable|image|max:2048',
        ];
    }
}
