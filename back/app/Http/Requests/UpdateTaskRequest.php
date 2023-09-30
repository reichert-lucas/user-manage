<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'nullable|string|min:3',
            'status_id' => 'nullable|integer|exists:task_statuses,id',
        ];
    }
}
