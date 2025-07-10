<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Helpers\GroupCategories;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Group::query();
        $categories = GroupCategories::all();

        // Filtros
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('category')) {
            $query->where('category', 'like', '%' . $request->category . '%');
        }
        if ($request->filled('is_private')) {
            $query->where('is_private', $request->is_private);
        }
        if ($request->filled('invite_code')) {
            $query->where('invite_code', $request->invite_code);
        }

        // Grupos privados donde el usuario es miembro o admin
        $privateGroups = collect();
        if ($user) {
            if ($user->is_admin) {
                $privateGroups = $query->clone()->where('is_private', true)->get();
            } else {
                $privateGroups = $user->groups()->where('is_private', true)
                    ->when($request->filled('name'), fn($q) => $q->where('name', 'like', '%' . $request->name . '%'))
                    ->when($request->filled('category'), fn($q) => $q->where('category', 'like', '%' . $request->category . '%'))
                    ->when($request->filled('invite_code'), fn($q) => $q->where('invite_code', $request->invite_code))
                    ->get();
                // Si busca por código y no pertenece, agregar el grupo privado encontrado
                if ($request->filled('invite_code')) {
                    $extraGroup = Group::where('is_private', true)
                        ->where('invite_code', $request->invite_code)
                        ->first();
                    if ($extraGroup && !$user->groups->contains($extraGroup->id)) {
                        $privateGroups->push($extraGroup);
                    }
                }
            }
        }
        // Grupos públicos
        $publicGroups = $query->clone()->where('is_private', false)->get();
        return view('groups.index', compact('privateGroups', 'publicGroups', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = GroupCategories::all();
        return view('groups.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:100',
            'invite_code' => 'nullable|string|max:255',
            'is_private' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('groups', 'public');
            $validated['image'] = $imagePath;
        }
        $validated['creator_id'] = $user->id;
        $group = \App\Models\Group::create($validated);
        // El creador es admin automáticamente
        $group->memberships()->create([
            'user_id' => $user->id,
            'role' => 'admin',
            'joined_at' => now(),
        ]);
        return redirect()->route('groups.show', $group)->with('success', 'Grupo creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $group = Group::with(['creator', 'memberships.user'])->findOrFail($id);
        return view('groups.show', compact('group'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $group = Group::findOrFail($id);
        $user = auth()->user();
        $isWebMaster = $user->is_admin;
        $isGroupAdmin = $group->memberships()->where('user_id', $user->id)->where('role', 'admin')->exists();
        if (!$isWebMaster && !$isGroupAdmin) {
            abort(403, 'No tienes permisos para editar este grupo.');
        }
        $categories = GroupCategories::all();
        return view('groups.edit', compact('group', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $group = Group::findOrFail($id);
        $user = auth()->user();
        $isWebMaster = $user->is_admin;
        $isGroupAdmin = $group->memberships()->where('user_id', $user->id)->where('role', 'admin')->exists();
        if (!$isWebMaster && !$isGroupAdmin) {
            abort(403, 'No tienes permisos para editar este grupo.');
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'nullable|string|max:100',
            'invite_code' => 'nullable|string|max:255',
            'is_private' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        // Procesar imagen si se subió
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('groups', 'public');
            $validated['image'] = $imagePath;
        }
        $group->update($validated);
        return redirect()->route('groups.index')->with('success', 'Grupo actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $group = Group::findOrFail($id);
        $user = auth()->user();
        // Permitir solo si el usuario es admin global o tiene rol 'admin' en el grupo
        $isWebMaster = $user->is_admin;
        $isGroupAdmin = $group->memberships()->where('user_id', $user->id)->where('role', 'admin')->exists();
        if (!$isWebMaster && !$isGroupAdmin) {
            abort(403, 'No tienes permisos para eliminar este grupo.');
        }
        // Desligar recetas
        foreach ($group->recipes as $recipe) {
            $recipe->group_id = null;
            $recipe->save();
        }
        // Eliminar membresías
        $group->memberships()->delete();
        $group->delete();
        return redirect()->route('groups.index')->with('success', 'Grupo eliminado correctamente.');
    }
}
