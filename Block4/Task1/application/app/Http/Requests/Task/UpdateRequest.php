<?php

namespace App\Http\Requests\Task;

use App\Enums\Task;
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string', 'max:255', 'filled'],
            'description' => ['nullable', 'string', 'max:255', 'filled'],
            'status' => ['nullable', new Enum(Task::class), 'filled'],
        ];
    }
    public function messages(): array
    {
        $tasksStatusValues = "";
        foreach (Task::cases() as $case) {
            $tasksStatusValues .= $case->name . ", ";
        }
        return [
            'status.in' => 'Status must be on of these: ' . $tasksStatusValues,
        ];
    }
}
