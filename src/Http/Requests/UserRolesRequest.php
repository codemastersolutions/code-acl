<?php

namespace CodeMaster\CodeAcl\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRolesRequest extends FormRequest
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
        $model = app(config('code-acl.models.role.class'));
        $conn = $model->getConnectionName();
        $table = $model->getTable();

        return [
            'roles' => "required|array",
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
            'roles.required' => 'Campo roles é obrigatório',
            'roles.array' => 'Campo roles deve ser um array',
        ];
    }
}
