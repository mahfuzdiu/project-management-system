<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'status' => 'sometimes|string|in:pending,in-progress,done',
            'due_date' => 'sometimes|date',
            'project_id' => 'sometimes|exists:projects,id',
            'assigned_to' => 'sometimes|exists:users,id',
        ];
    }
}
