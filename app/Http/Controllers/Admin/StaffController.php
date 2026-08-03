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
        $staff = auth()->user()->restaurant->users()
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

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($password),
            'restaurant_id' => auth()->user()->restaurant_id,
            'role' => 'staff',
        ]);

        return back()->with('staff_password', $password)->with('staff_email', $request->email);
    }

    public function destroy(User $user)
    {
        // Only allow deleting staff from same restaurant
        if ($user->restaurant_id !== auth()->user()->restaurant_id || $user->role !== 'staff') {
            abort(403);
        }
        $user->delete();
        return back()->with('success', 'Empleado eliminado.');
    }
}
