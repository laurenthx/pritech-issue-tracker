<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // For now, allow any authenticated user to add a comment.
                     // You could add more complex policy-based authorization here.
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // 'issue_id' will be sent as a hidden input from the form (as per your show.blade.php)
            'issue_id' => ['required', 'exists:issues,id'],
            'author_name' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'issue_id.required' => 'The issue ID is missing.',
            'issue_id.exists' => 'The specified issue does not exist.',
            'author_name.required' => 'Your name is required.',
            'author_name.string' => 'Your name must be text.',
            'author_name.max' => 'Your name cannot be longer than 255 characters.',
            'body.required' => 'The comment body cannot be empty.',
            'body.string' => 'The comment body must be text.',
        ];
    }
}