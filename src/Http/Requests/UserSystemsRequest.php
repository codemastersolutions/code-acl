<?php

namespace CodeMaster\CodeAcl\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserSystemsRequest extends FormRequest
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
            'systems' => "required|array",
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'systems.required' => 'Campo systems é obrigatório',
            'systems.array' => 'Campo systems deve ser um array',
        ];
    }
}
