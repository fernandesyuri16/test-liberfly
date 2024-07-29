<?php

namespace App\Http\Requests\HolidayPlan;

use Illuminate\Foundation\Http\FormRequest;

class StoreHolidayPlanRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|unique:holiday_plans|max:50',
            'description' => 'required|max:150',
            'date' => 'required|date_format:Y-m-d',
            'location' => 'required|max:20',
            'participants' => 'nullable|max:50',
        ];
    }
}
