<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class updateRolRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->can('editar rol');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:roles,name,' . $this->role->id,
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del rol es obligatorio',
            'name.string' => 'El nombre del rol debe ser una cadena de texto',
            'name.unique' => 'Ya existe un rol con este nombre',

        ];
    }
}
