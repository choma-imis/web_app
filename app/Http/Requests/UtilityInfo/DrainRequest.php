<?php

namespace App\Http\Requests\UtilityInfo;

use App\Http\Requests\Request;

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
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'diameter' => $this->cleanNumber($this->input('diameter')),
            'length' => $this->cleanNumber($this->input('length')),
        ]);
    }

    /**
     * Remove commas from number inputs.
     */
    private function cleanNumber($value)
    {
        return $value !== null ? str_replace(',', '', $value) : null;
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
                return [];

            case 'POST':
            case 'PUT':
            case 'PATCH':
                return [
                    'diameter' => 'nullable|numeric',
                    'length' => 'nullable|numeric',
                    'type' => 'nullable|string',
                ];

            default:
                return [];
        }
    }

     public function messages()
    {
        return [
            'name.regex' => __('The name field should contain only contain letters and spaces.'),
            'diameter.numeric' => __('The Diameter must be a number.'),
            'length.numeric' => __('The Length (m) must be a number.'),
            ];
    }
}
