<?php

namespace App\Http\Requests\UtilityInfo;

use App\Http\Requests\Request;
use App\Models\UtilityInfo\Roadline;

class SewerLineRequest extends Request
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
        
        switch ($this->method()) {
            case 'GET':
            case 'DELETE':
                {
                    return [];
                }
            case 'POST':
                {
                    return [
                        'road_code' => 'required|string',
                        'location' => 'required|string',
                        'length' => 'required|numeric',
                        'diameter' => 'required|numeric',
                        'treatment_plant_id'  => 'nullable',
                    ];
                }
            case 'PUT':
            case 'PATCH':
                {
                    return [
                       'location' => 'required|string',
                        'length' => 'required|numeric',
                        'diameter' => 'required|numeric',
                        'treatment_plant_id'  => 'nullable',
                    ];
                }
            default:break;
        }
    }
     public function messages()
    {
        return [
            'name.regex' => 'The name field should contain only contain letters and spaces.',
            'length.numeric' => 'The Length(m) must be a number.',
            'length.required' => 'The Length(m) is required.',
            'location.string' => 'The Location must be a string.',
            'location.required' => 'The Location is required.',
            'diameter.numeric' => 'The Diameter(mm) must be a number.',
            'diameter.required' => 'The Diameter(mm) is required.',
            ];
    }
}
