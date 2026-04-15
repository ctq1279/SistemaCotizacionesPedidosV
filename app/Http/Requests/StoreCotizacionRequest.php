<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCotizacionRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'cliente_id' => 'required|exists:clientes,id',
            //'impuestos' => 'nullable',
            'impuestos' => 'nullable|numeric',

            'fecha_hora' => 'required|date',
            //'estado' => 'required|string|max:50',
            'total' => 'required|numeric|min:0',
            'tiempo_entrega' => 'nullable|string|max:255',
            'lugar_entrega'=> 'nullable|string|max:255',

            //'costo_mano_obra' => 'nullable|numeric|min:0',
            //'costo_materiales' => 'nullable|numeric|min:0',
            // Validación de arrays para la tabla `cotizaciones_productos`
            //'cantidad' => 'required|integer|min:0',
        ];
    }
}
