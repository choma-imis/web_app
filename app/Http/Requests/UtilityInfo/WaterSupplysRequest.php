<?php

namespace App\Http\Requests\UtilityInfo;

use App\Http\Requests\Request;
use App\Models\UtilityInfo\WaterSupplys;

class WaterSupplysRequest extends Request
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
        
        switch ($this->method()) 
        {
            case 'GET':
            case 'DELETE':
                {
                    return [];
                }
            case 'POST':
                {
                    return [
                        'road_code' => 'required',
                        'project_name'=> 'required|string',
                        'type'=>'nullable|string',
                        'material_type'=>'nullable|string',
                        'diameter' => 'required|numeric',
                        'length' => 'required|numeric',
                       
                    ];
                }
            case 'PUT':
            case 'PATCH':
                {
                    return [
                        'project_name'=> 'required|string',
                        'type'=>'nullable|string',
                        'material_type'=>'nullable|string',
                        'diameter' => 'required|numeric',
                        'length' => 'required|numeric',
                    ];
                }
            default:break;
        }
    }
    
    public function messages()
    {
        return [
            'road_code.required' => 'The Road Code is required.',
            'project_name.required' => 'The Project Name is required.',
            'project_name.string' => 'The Project Name must be a string.',
            'type.string' => 'The Type must be a string.',
            'material_type.string' => 'The Material Type must be a string.',
            'diameter.required' => 'The Diameter (mm) is required.',
            'diameter.numeric' => 'The Diameter (mm) must be a number.',
            'length.required' => 'The Length (m) is required.',
            'length.numeric' => 'The Length (m) must be a number.',
            'name.regex' => 'The name field should contain only letters and spaces.',
            'name.string' => 'This Project Name must be a string.',
        ];
    }

}
