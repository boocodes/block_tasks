<?php

namespace App\Http\Requests\Task;

use App\Enums\Priority;
use App\Enums\TaskStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class CreateRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'project_id' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'status' => ['required', new Enum(TaskStatus::class)],
            'priority' => ['required', new Enum(Priority::class)],
            'due_date' => ['required']
        ];
    }
    public function messages()
    {
        return [
            'project_id.required' => 'Project id is required',
            'title.required' => 'Title is required',
            'description.required' => 'Description is required',
            'status.required' => 'Status is required',
            'priority.required' => 'Priority is required',
            'due_date.required' => 'Due date is required'
        ];  
    }
}
