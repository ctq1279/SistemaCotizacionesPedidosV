<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;
    protected $fillable = [
        'cotizacione_id',
        'comprobante_id',
        'numero_comprobante',
        'cliente_id',
        'fecha_pedido',
        'producto_id',
        'tiempo_entrega',
        'tipo_envio',
        'user_id',
        'total',
        'estado',
    ];
    
    public function productos()
    {
        return $this->belongsToMany(Producto::class,'pedidos_producto_tabla')->withPivot('cantidad', 'precio_pedido', 'descuento_porcentaje', 'descuento_monto', 'total_pedido');
    }

    public function cotizacione()
    {
        return $this->belongsTo(Cotizacione::class);
    }

    public function venta()
    {
        return $this->hasMany(Venta::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comprobante()
    {
        return $this->belongsTo(Comprobante::class, 'comprobante_id');
    }
    // Relación con el modelo Cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);  // Establece que cada pedido pertenece a un cliente
    }
}
