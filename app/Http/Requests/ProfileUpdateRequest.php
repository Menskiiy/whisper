<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()?->id)],

            // ←←←←←←←←←←←←←←←←←←←←←←←←←←←←←←←←
            // НАШИ КАСТОМНЫЕ ПОЛЯ:
            'status'    => ['nullable', 'string', 'max:100'],
            'bio'       => ['nullable', 'string', 'max:160'],
            'birthday'  => ['nullable', 'date', 'before:today'],
            'location'  => ['nullable', 'string', 'max:100'],
            'avatar'    => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            // ↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
        ];
    }
}