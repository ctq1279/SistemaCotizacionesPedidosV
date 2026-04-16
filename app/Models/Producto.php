<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Producto extends Model
{
    use HasFactory;

    public function materiale()
    {
        return $this->belongsToMany(Materiale::class, 'material_producto_tabla')->withTimestamps();
    }

    // Método para calcular el costo total de materiales en el producto
    
    public function categorias()
    {
        return $this->belongsToMany(Categoria::class, 'categoria_producto_tabla')->withTimestamps();
    }

    public function cotizaciones()
    {
        return $this->belongsToMany(Cotizacione::class, 'cotizacion_producto_tabla')->withPivot('cantidad', 'costo_materiales', 'precio_cotizacion', 'descuento_porcentaje', 'descuento_monto');
    }

    public function pedidos()
    {
        return $this->belongsToMany(Pedido::class)->withPivot('cantidad', 'precio_pedido', 'descuento_porcentaje', 'descuento_monto', 'total_pedido');
    }

    public function ventas()
    {
        return $this->belongsToMany(Venta::class)->withTimestamps()->withPivot('cantidad', 'precio_venta', 'descuento_porcentaje', 'descuento_monto', 'total_venta');
    }

    protected $fillable = ['nombre', 'descripcion', 'precio_unitario', 'costo_mano_obra', 'costo_total_materiales', 'stock', 'talla', 'color', 'genero', 'img_path','logos_insignias','forro','material_tela'];

    public function handleUploadImage($image)
    {
        $file = $image;
        $name = time() . '.' . $file->getClientOriginalName();
        //$file->move(public_path().'/img/productos/',$name);
        Storage::putFileAs('public/productos', $file, $name, 'public');
        return $name;
    }
}
