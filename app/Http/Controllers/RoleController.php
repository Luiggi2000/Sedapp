<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::paginate(10);
        return view('roles.index', compact('roles'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'guard_name' => 'required|string|max:255',
        ]);

        Role::create($request->all());

        return redirect()->route('roles.index')->with('success', 'Rol creado exitosamente.');
    }
    
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'guard_name' => 'required|string|max:255',
        ]);

        $role->update($request->all());

        return redirect()->route('roles.index')->with('success', 'Rol actualizado exitosamente.');
    }
    
    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Rol eliminado exitosamente.');
    }
}
