<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|unique:tasks,name|max:255',
            'status_id' => 'required|exists:task_statuses,id',
            'assigned_to_id' => 'required',
            'description' => 'nullable|string',
            'labels' => 'nullable|array',
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
            'name.required' => __('controllers.required_error'),
            'name.unique' => __('controllers.unique_error_task'),
            'status_id.required' => __('controllers.required_error'),
            'assigned_to_id.required' => __('controllers.required_error'),
            'name.max' => __('controllers.max_error'),
        ];
    }
}
