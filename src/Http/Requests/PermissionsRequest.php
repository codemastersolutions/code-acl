<?php

namespace CodeMaster\CodeAcl\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermissionsRequest extends FormRequest
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
        $unique = "{$conn}.{$table},name";

        return [
            'name' => "required|string|max:50|unique:{$unique}",
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
            'name.required' => 'Campo name é obrigatório',
            'name.string' => 'Campo name deve ser alfanumérico',
            'name.max' => 'Campo name deve ter no máximo 50 caracteres',
            'name.unique' => 'Campo name deve ser único',
        ];
    }
}
