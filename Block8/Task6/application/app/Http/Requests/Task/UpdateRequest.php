<?php

namespace Final6\App\Http\Requests\Task;

use Final6\App\Enums\Priority;
use Final6\App\Enums\TaskStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateRequest extends FormRequest
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
            'title' => ['string', 'max:255'],
            'description' => ['string', 'max:255'],
            'status' => [new Enum(TaskStatus::class)],
            'priority' => [ new Enum(Priority::class)],
            'due_date' => ['string']
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Title is required',
            'description.required' => 'Description is required',
            'status.required' => 'Status is required',
            'priority.required' => 'Priority is required',
            'due_date.required' => 'Due date is required',
        ];
    }
}
