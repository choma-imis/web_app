<?php

namespace App\Http\Requests\UtilityInfo;

use App\Http\Requests\Request;
use App\Models\UtilityInfo\Roadline;

class DrainRequest extends Request
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
                        'cover_type' => 'nullable',
                        'surface_type' => 'nullable',
                        'size' => 'required|numeric',
                        'length' => 'required|numeric',
                        'treatment_plant_id'  => 'nullable',
                    ];
                }
            case 'PUT':
            case 'PATCH':
                {
                    return [
                        'cover_type' => 'nullable',
                        'surface_type' => 'nullable',
                        'size' => 'required|numeric',
                        'length' => 'required|numeric',
                        'treatment_plant_id'  => 'nullable',
                    ];
                }
            default:break;
        }
    }
     public function messages()
    {
        return [
            'road_code.required' => 'The Road Code is required.' ,
            'size.required' => 'The Width (mm) is required.',
            'size.numeric' => 'The  Width (mm) must be a number.',
            'name.regex' => 'The name field should contain only contain letters and spaces.',
            'diameter.numeric' => 'The Diameter must be a number.',
            'length.numeric' => 'The Length (m) must be a number.',
            'length.required' => 'The Length (m) is required.',
            ];
    }
}
