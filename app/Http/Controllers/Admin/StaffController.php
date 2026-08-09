<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StaffController extends AdminController
{
    public function index()
    {
        $staff = $this->restaurant()->users()
            ->where('role', 'staff')
            ->orderBy('name')
            ->get();
        return view('admin.staff.index', compact('staff'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
        ], [
            'name.required' => 'El nombre es requerido.',
            'email.required' => 'El correo es requerido.',
            'email.email' => 'El correo no es válido.',
            'email.unique' => 'Ya existe un usuario con ese correo.',
        ]);

        $password = Str::random(10);

        // Si el usuario ya existe, sumarlo al pivote sin duplicar
        $existing = User::where('email', $request->email)->first();
        if ($existing) {
            $this->restaurant()->members()->syncWithoutDetaching([
                $existing->id => ['role' => 'staff'],
            ]);
            return back()->with('success', "El usuario {$existing->name} ya existía y fue agregado como staff.");
        }

        $user = User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($password),
            'restaurant_id' => $this->restaurant()->id,
            'role'          => 'staff',
        ]);

        $this->restaurant()->members()->attach($user->id, ['role' => 'staff']);

        return back()->with('staff_password', $password)->with('staff_email', $request->email);
    }

    public function destroy(User $user)
    {
        if ($user->restaurant_id !== $this->restaurant()->id || $user->role !== 'staff') {
            abort(403);
        }
        $user->delete();
        return back()->with('success', 'Empleado eliminado.');
    }
}
