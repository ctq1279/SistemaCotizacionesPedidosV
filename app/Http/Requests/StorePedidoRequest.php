<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePedidoRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'fecha_pedido' => 'required|date', // Asegura que fecha_pedido sea obligatorio y tenga un formato de fecha válido
            'tipo_envio' => 'nullable|string|max:105', // 'tipo_envio' es opcional, debe ser un string de máximo 105 caracteres
            'tiempo_entrega' => 'nullable|string|max:105', // 'tiempo_entrega' es opcional, debe ser un string de máximo 105 caracteres
            'impuestos' => 'nullable|numeric|min:0', // 'impuestos' es opcional, debe ser numérico y mayor o igual a 0
            'total' => 'required|numeric|min:0', // 'total' es obligatorio, debe ser numérico y mayor o igual a 0
            'costo_mano_obra' => 'nullable|numeric|min:0', // 'costo_mano_obra' es opcional, debe ser numérico y mayor o igual a 0
            'estado' => 'required|in:pendiente,en_proceso,entregado,cancelado', // 'estado' es obligatorio y debe ser uno de los valores especificados
            'user_id' => 'nullable|exists:users,id', // 'user_id' es opcional, debe existir en la tabla 'users'
            'cotizacione_id' => 'required|exists:cotizaciones,id', // 'cotizacione_id' es obligatorio y debe existir en la tabla 'cotizaciones'
            'cliente_id' => 'required|exists:clientes,id', // 'cliente_id' es obligatorio y debe existir en la tabla 'clientes'

        ];
    }
}
