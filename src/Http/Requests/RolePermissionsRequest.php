<?php

namespace CodeMaster\CodeAcl\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RolePermissionsRequest extends FormRequest
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
        $model = app(config('code-acl.models.permission.class'));
        $conn = $model->getConnectionName();
        $table = $model->getTable();

        return [
            'permissions' => "required|array",
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
            'permissions.required' => 'Campo permissions é obrigatório',
            'permissions.array' => 'Campo permissions deve ser um array',
        ];
    }
}
