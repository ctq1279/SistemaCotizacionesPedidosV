<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materiale extends Model
{
    use HasFactory;

    public function compras(){
        return $this->belongsToMany(Compra::class)->withTimestamps()->withPivot('cantidad','precio_compra','precio_venta');
    }

    public function productos(){
        return $this->belongsToMany(Producto::class,'material_producto_tabla')->withTimestamps();
    }

    protected $fillable = ['nombre', 'descripcion', 'unidad_medida', 'precio','cantidad'];

}
