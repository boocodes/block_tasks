<?php

namespace Task2\App\Http\Requests\Task;

use Task2\App\Enums\TaskStatus;
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:512', 'filled'],
            'status' => ['nullable', new Enum(TaskStatus::class), 'filled'],
        ];
    }
    public function messages(): array
    {
        $tasksStatusValues = "";
        foreach (TaskStatus::cases() as $case) {
            $tasksStatusValues .= $case->name . ", ";
        }
        return [
            'title.required' => 'Title fields is required.',
            'title.string' => 'Title fields must be a string.',
            'title.max' => 'Title fields cannot be longer than 255 characters.',
            'description.max' => 'Description fields cannot be longer than 512 characters.',
            'status' => 'Status must be on of these: ' . $tasksStatusValues,
        ];
    }
}
