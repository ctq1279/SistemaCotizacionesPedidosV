<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class permissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    
    public function run()
    {

        $permisos = [
            //categorias
            'ver-categoria',
            'crear-categoria',
            'editar-categoria',
            'eliminar-categoria',

            //clientes
            'ver-cliente',
            'crear-cliente',
            'editar-cliente',
            'eliminar-cliente',

            //proveedores
            'ver-proveedor',
            'crear-proveedor',
            'editar-proveedor',
            'eliminar-proveedor',

            //materiales
            'ver-material',
            'crear-material',
            'editar-material',
            'eliminar-material',

            //prendas
            'ver-producto',
            'crear-producto',
            'editar-producto',
            'eliminar-producto',

            //cotizaciones
            'ver-cotizacion',
            'crear-cotizacion',
            'editar-cotizacion',
            'eliminar-cotizacion',

            //pedidos
            'ver-pedido',
            'crear-pedido',
            'editar-pedido',
            'eliminar-pedido',

            //compras
            'ver-compra',
            'crear-compra',
            'mostrar-compra',
            'eliminar-compra',

            //ventas
            'ver-venta',
            'crear-venta',
            'mostrar-venta',
            'eliminar-venta',

            //reportes
            'generar-reporte',

            //roles
            'ver-role',
            'crear-role',
            'editar-role',
            'eliminar-role',

            //users
            'ver-user',
            'crear-user',
            'editar-user',
            'eliminar-user',
        ];

        foreach($permisos as $permiso){
            Permission::create(['name' => $permiso]);
        }
    }
}
