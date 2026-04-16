<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductoRequest extends FormRequest
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
            'nombre' => 'required|max:100',
            'descripcion' => 'nullable|max:255',
            'precio_unitario' => 'required|numeric',
            'talla' => 'nullable|max:45',
            'color' => 'nullable|max:45',
            'genero' => 'nullable|max:45',
            'costo_mano_obra' => 'nullable|numeric',
            'img_path' => 'nullable|image|mimes:png,jpg,jpeg|max:255',
            'categorias' => 'required|array',
            'materiales' => 'nullable|array', // Materiales es opcional
            'materiales.*' => 'integer|exists:materiales,id', // Valida que los materiales existan
            'costo_total_materiales' => 'nullable|numeric',
            'logos_insignias' => 'nullable|string|max:255',
            'forro' => 'nullable|string|max:255',
            'material_tela' => 'nullable|string|max:255',
        ];
    }

    public function attributes()
    {
        return [
            'precio_unitario' => 'precio',

        ];
    }
}
