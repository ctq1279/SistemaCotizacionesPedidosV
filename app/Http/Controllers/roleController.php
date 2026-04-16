<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class roleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {    
        $this->middleware('permission:ver-role|crear-role|editar-role|eliminar-role', ['only' => ['index']]);
        $this->middleware('permission:crear-role', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-role', ['only' => ['edit', 'update']]);
        $this->middleware('permission:eliminar-role', ['only' => ['destroy']]);
    }

    public function index()
    {
        $roles = Role::all();
        return view('role.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permisos = Permission::all();
        return view('role.create', compact('permisos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validar datos
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permission' => 'required|array',
        ]);

        // Registrar en log los datos enviados
        Log::info('Datos enviados desde el formulario:', $request->all());

        try {
            // Iniciar la transacción
            DB::beginTransaction();
            Log::info('Iniciando la creación del rol.');

            // Crear el rol
            $role = Role::create(['name' => $request->name]);
            Log::info('Rol creado:', ['id' => $role->id, 'name' => $role->name]);

            // Asignar permisos al rol uno a uno
            foreach ($request->permission as $permissionId) {
                $permission = Permission::find($permissionId);
                if ($permission) {
                    $role->givePermissionTo($permission);
                    Log::info("Permiso asignado: " . $permission->name);
                } else {
                    Log::error("Permiso con ID $permissionId no existe.");
                }
            }

            // Confirmar transacción
            DB::commit();
            Log::info('Rol creado exitosamente con todos sus permisos.');

            // Redirigir al índice de roles con un mensaje de éxito
            return redirect()->route('roles.index')->with('success', 'Rol creado exitosamente con permisos asignados.');
        } catch (\Exception $e) {
            // Rollback si algo falla
            DB::rollBack();
            Log::error('Error al crear el rol:', ['message' => $e->getMessage()]);
            return redirect()->route('roles.index')->with('error', 'Error al crear el rol: ' . $e->getMessage());
        }
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $permisos = Permission::all();
        return view('role.edit', compact('role', 'permisos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|unique:roles,name,' . $id, // Asegúrese de que el nombre sea único
                'permission' => 'required|array', // Asegúrese de que se reciba un array de permisos
            ]);

            // Buscar el rol por su ID
            $role = Role::findOrFail($id);

            // Registrar en el log
            Log::info('Iniciando la actualización del rol.', ['role_id' => $role->id]);

            // Actualizar el nombre del rol
            $role->name = $request->name;
            $role->save(); // Guardar el rol con el nuevo nombre

            // Asegurarse de que los permisos recibidos existen
            $permissions = Permission::whereIn('id', $request->permission)->get();
            if ($permissions->count() != count($request->permission)) {
                Log::error("Uno o más permisos no existen.");
                return response()->json([
                    'message' => 'Algunos permisos no existen.',
                ], 400);
            }

            // Asignar los permisos al rol
            $role->syncPermissions($permissions);
            Log::info('Permisos asignados correctamente al rol.', ['role_id' => $role->id]);

            return redirect()->route('roles.index')->with('success', 'Rol actualizado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al actualizar el rol: ' . $e->getMessage());

            return redirect()->route('roles.index')->with('error', 'Error al actualizar el rol: ' . $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::find($id);
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Rol eliminado');
    
    }
}
