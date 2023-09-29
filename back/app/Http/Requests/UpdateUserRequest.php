<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'nullable|string|min:2|max:255',
            'email' => 'nullable|email',
            'password' => 'nullable|string|min:8|max:255',
        ];
    }
}
