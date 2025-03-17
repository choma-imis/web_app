<?php

namespace App\Http\Requests\Language;

use Illuminate\Foundation\Http\FormRequest;

class LanguageRequest extends FormRequest
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
    public function rules()
    {
        $rules = ($this->isMethod('POST') ? $this->store() : $this->update());
        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'Language Name required.',

        'short.required' => 'Language Short required.',
        'code.required' => 'Language Code required.',
        'status.required' => 'Language Status required.',
        'code.unique' => 'Language Code already exists.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function store()
{
    return [
        'name' => 'required',
        'short' => 'required',
        'status' => 'required',
        'code' => 'required|unique:pgsql.language.languages,code',
    ];
}


    public function update()
    {
        return [
            'name' => 'required',
            'short' => 'required',
            'status' => 'required',
            'code' => 'required|unique:pgsql.language.languages,code',
        ];
    }
}
