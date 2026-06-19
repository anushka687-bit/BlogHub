<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBlogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'cover_image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
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
            'cover_image.required' => 'Please upload a cover image.',
            'cover_image.image' => 'The cover file must be an image.',
            'short_description.required' => 'A short description is required.',
            'content.required' => 'Blog content cannot be empty.',
            'category.required' => 'Please select a category.',
        ];
    }
}