<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePhysicalInfoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Solo permitir si el usuario está autenticado y es enfermero
        return auth()->check() && auth()->user()->role === 'enfermero';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'age' => 'required|integer|min:15|max:100',
            'date_of_birth' => 'required|date|before:today',
            'height' => 'required|numeric|min:1|max:3',
            'gender' => 'required|in:masculino,femenino,otro',
            'weight' => 'required|numeric|min:20|max:300',
            'condition' => 'nullable|string|max:1000',
            'recommendation' => 'nullable|string|max:1000',
            'permisos' => 'required|in:libre,limitado',
        ];
    }

    public function messages(): array
    {
        return [
            'date_of_birth.before' => 'La fecha de nacimiento debe ser anterior a hoy.',
            'age.min' => 'La edad mínima permitida es 15 años.',
            'age.max' => 'La edad máxima permitida es 100 años.',
            'height.min' => 'La altura mínima válida es 1 metro.',
            'height.max' => 'La altura máxima válida es 3 metros.',
            'weight.min' => 'El peso mínimo permitido es 20 kg.',
            'weight.max' => 'El peso máximo permitido es 300 kg.',
            'permisos.in' => 'Selecciona un permiso válido (libre o limitado).',
        ];
    }
}
