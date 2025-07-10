<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Membership;
use App\Models\Group;

class MembershipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $validated = $request->validate([
            'group_id' => 'required|exists:groups,id',
            'invite_code' => 'nullable|string',
        ]);
        $group = \App\Models\Group::findOrFail($validated['group_id']);
        // No permitir duplicados
        if ($group->memberships()->where('user_id', $user->id)->exists()) {
            return back()->with('info', 'Ya eres miembro o tienes una solicitud pendiente para este grupo.');
        }
        if ($group->is_private) {
            // Privado: requiere código y queda como pending
            if (!$validated['invite_code'] || $group->invite_code !== $validated['invite_code']) {
                return back()->withErrors(['invite_code' => 'Código de invitación incorrecto.']);
            }
            $role = 'pending';
        } else {
            // Público: se une directo como member
            $role = 'member';
        }
        $group->memberships()->create([
            'user_id' => $user->id,
            'role' => $role,
            'joined_at' => now(),
        ]);
        return back()->with('success', $group->is_private ? 'Solicitud enviada. Espera la aprobación del admin.' : 'Te has unido al grupo.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $membership = Membership::findOrFail($id);
        $group = $membership->group;
        $user = auth()->user();
        $isWebMaster = $user->is_admin;
        $isGroupAdmin = $group->memberships()->where('user_id', $user->id)->where('role', 'admin')->exists();
        // No permitir cambiar el rol del creador
        if ($membership->user_id == $group->creator_id) {
            abort(403, 'No puedes cambiar el rol del creador del grupo.');
        }
        if (!$isWebMaster && !$isGroupAdmin) {
            abort(403, 'No tienes permisos para cambiar el rol de este usuario.');
        }
        $validated = $request->validate([
            'role' => 'required|in:member,admin',
        ]);
        $membership->role = $validated['role'];
        $membership->save();
        return back()->with('success', 'Rol actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $membership = Membership::findOrFail($id);
        $group = $membership->group;
        $user = auth()->user();
        $isWebMaster = $user->is_admin;
        $isGroupAdmin = $group->memberships()->where('user_id', $user->id)->where('role', 'admin')->exists();
        // No permitir eliminar al creador
        if ($membership->user_id == $group->creator_id) {
            abort(403, 'No puedes eliminar al creador del grupo.');
        }
        if (!$isWebMaster && !$isGroupAdmin) {
            abort(403, 'No tienes permisos para eliminar este usuario.');
        }
        $membership->delete();
        return back()->with('success', 'Usuario eliminado del grupo.');
    }
}
