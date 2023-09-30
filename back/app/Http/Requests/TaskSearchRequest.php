<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskSearchRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => 'nullable|integer|exists:tasks,id',
            'search' => 'nullable|string',
            'status_ids' => 'nullable|array',
            'status_ids.*' => 'required|integer|exists:task_statuses,id',
        ];
    }
}
