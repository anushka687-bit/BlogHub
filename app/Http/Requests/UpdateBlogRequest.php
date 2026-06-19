<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBlogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'category' => ['required', 'string', 'max:100'],
            'tags' => ['nullable', 'string'],
            'short_description' => ['required', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'status' => ['required', 'in:draft,published'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The blog title is required.',
            'short_description.required' => 'A short description is required.',
            'content.required' => 'Blog content cannot be empty.',
            'category.required' => 'Please select a category.',
        ];
    }
}