<?php

namespace App\Http\Requests\HolidayPlan;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHolidayPlanRequest extends FormRequest
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
            'title' => 'nullable|max:50',
            'description' => 'nullable|max:150',
            'date' => 'nullable|date_format:Y-m-d',
            'location' => 'nullable|max:20',
            'participants' => 'nullable|max:50',
        ];
    }
}
