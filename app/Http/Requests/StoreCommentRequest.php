<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'comment' => ['required', 'string', 'max:1000'],
            'parent_id' => ['nullable', 'exists:comments,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'comment.required' => 'Comment cannot be empty.',
        ];
    }
}