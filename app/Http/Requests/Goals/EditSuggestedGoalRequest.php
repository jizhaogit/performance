<?php

namespace App\Http\Requests\Goals;

use Illuminate\Foundation\Http\FormRequest;

class EditSuggestedGoalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'what' => 'required',
            'why' => 'nullable',
            'how' => 'nullable',
            'measure_of_success' => 'nullable',
            'goal_type_id' => 'required|exists:goal_types,id'
        ];
    }
}
