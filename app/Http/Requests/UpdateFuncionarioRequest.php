<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFuncionarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'            => 'required|string|max:120',
            'apellido_paterno'  => 'required|string|max:120',
            'apellido_materno'  => 'required|string|max:120',
            'cargo_id'          => 'required|exists:cargos,id',
            'tipo_contrato_id'  => 'required|exists:tipo_contratos,id',

            'region_id'         => 'nullable|exists:regiones,id',
            'provincia_id'      => 'nullable|exists:provincias,id',
            'comuna_id'         => 'nullable|exists:comunas,id',
            'direccion'         => 'nullable|string|max:255',
        ];
    }
}
