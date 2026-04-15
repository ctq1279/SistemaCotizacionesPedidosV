<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cotizacione extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'impuestos',
        'costo_mano_obra',
        //'user_id',
        'fecha_hora',
        'total',
        'costo_materiales',
        'cantidad',
        'tiempo_entrega',
        'lugar_entrega',
    ];

    public function cliente(){
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function productos(){
        return $this->belongsToMany(Producto::class, 'cotizacion_producto_tabla')->withPivot('cantidad','costo_materiales','precio_cotizacion','descuento_porcentaje','descuento_monto','costo_produccion','costo_margen');
    }

    public function pedido(){
        return $this->hasMany(Pedido::class);
    }

    
}
