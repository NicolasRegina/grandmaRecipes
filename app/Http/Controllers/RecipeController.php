<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Recipe;
use App\Models\Group;
use App\Helpers\RecipeCategories;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = \App\Models\Recipe::with(['author', 'group']);
        if ($user) {
            $query->where(function($q) use ($user) {
                $q->where('is_private', false)
                  // Recetas privadas propias
                  ->orWhere(function($q2) use ($user) {
                      $q2->where('is_private', true)
                          ->where('author_id', $user->id);
                  })
                  // Recetas privadas de grupos a los que pertenece
                  ->orWhere(function($q2) use ($user) {
                      $q2->where('is_private', true)
                          ->whereNotNull('group_id')
                          ->whereIn('group_id', $user->groups->pluck('id'));
                  });
            });
        } else {
            $query->where('is_private', false);
        }
        // Filtros
        if ($request->filled('title')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->title . '%')
                  ->orWhere('description', 'like', '%' . $request->title . '%');
            });
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }
        if ($request->filled('is_private')) {
            $query->where('is_private', $request->is_private);
        }
        if ($request->filled('author')) {
            $query->whereHas('author', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->author . '%');
            });
        }
        $recipes = $query->orderBy('created_at', 'desc')->paginate(12);
        return view('recipes.index', compact('recipes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = RecipeCategories::all();

        // Solo los grupos a los que pertenece el usuario
        $allGroups = auth()->user()->groups;

        return view('recipes.create', compact('categories', 'allGroups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'ingredients' => 'required|array|min:1',
            'ingredients.*.name' => 'required|string|max:255',
            'ingredients.*.quantity' => 'required|string|max:50',
            'ingredients.*.unit' => 'nullable|string|max:50',
            'steps' => 'required|array|min:1',
            'steps.*.description' => 'required|string',
            'prep_time' => 'required|integer|min:1',
            'cook_time' => 'required|integer|min:0',
            'servings' => 'required|integer|min:1',
            'difficulty' => 'required|in:Fácil,Media,Difícil',
            'category' => 'required|string|max:100',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'group_id' => 'nullable|exists:groups,id',
            'is_private' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Procesar los pasos para agregar números secuenciales
        $steps = [];
        foreach ($validated['steps'] as $index => $step) {
            $steps[] = [
                'number' => $index + 1,
                'description' => $step['description']
            ];
        }
        $validated['steps'] = $steps;

        // Manejar la subida de imagen
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('recipes', 'public');
            $validated['image'] = $imagePath;
        }

        $validated['author_id'] = auth()->id();
        $validated['is_private'] = $request->has('is_private');

        Recipe::create($validated);

        return redirect()->route('recipes.index')->with('success', 'Receta creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Recipe $recipe)
    {
        // Verificar permisos para recetas privadas
        if ($recipe->is_private && (!auth()->check() ||
            (auth()->id() !== $recipe->author_id && !auth()->user()->is_admin))) {
            abort(403, 'No tienes permisos para ver esta receta.');
        }

        $recipe->load(['author', 'group']);
        return view('recipes.show', compact('recipe'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Recipe $recipe)
    {
        $user = auth()->user();
        $isOwner = $recipe->group && $recipe->group->creator_id === $user->id;
        // Solo el autor, un admin o el dueño del grupo pueden editar
        if ($user->id !== $recipe->author_id && !$user->is_admin && !$isOwner) {
            abort(403, 'No tienes permisos para editar esta receta.');
        }
        $categories = RecipeCategories::all();

        // Solo los grupos a los que pertenece el usuario
        $allGroups = auth()->user()->groups;

        return view('recipes.edit', compact('recipe', 'categories', 'allGroups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Recipe $recipe)
    {
        $user = auth()->user();
        $isOwner = $recipe->group && $recipe->group->creator_id === $user->id;
        // Solo el autor, un admin o el dueño del grupo pueden actualizar
        if ($user->id !== $recipe->author_id && !$user->is_admin && !$isOwner) {
            abort(403, 'No tienes permisos para actualizar esta receta.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'ingredients' => 'required|array|min:1',
            'ingredients.*.name' => 'required|string|max:255',
            'ingredients.*.quantity' => 'required|string|max:50',
            'ingredients.*.unit' => 'nullable|string|max:50',
            'steps' => 'required|array|min:1',
            'steps.*.description' => 'required|string',
            'prep_time' => 'required|integer|min:1',
            'cook_time' => 'required|integer|min:0',
            'servings' => 'required|integer|min:1',
            'difficulty' => 'required|in:Fácil,Media,Difícil',
            'category' => 'required|string|max:100',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'group_id' => 'nullable|exists:groups,id',
            'is_private' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Procesar los pasos
        $steps = [];
        foreach ($validated['steps'] as $index => $step) {
            $steps[] = [
                'number' => $index + 1,
                'description' => $step['description']
            ];
        }
        $validated['steps'] = $steps;

        // Manejar la imagen
        if ($request->hasFile('image')) {
            // Eliminar imagen anterior si existe
            if ($recipe->image) {
                Storage::disk('public')->delete($recipe->image);
            }
            $imagePath = $request->file('image')->store('recipes', 'public');
            $validated['image'] = $imagePath;
        }

        $validated['is_private'] = $request->has('is_private');

        $recipe->update($validated);

        return redirect()->route('recipes.show', $recipe)->with('success', 'Receta actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recipe $recipe)
    {
        $user = auth()->user();
        $isOwner = $recipe->group && $recipe->group->creator_id === $user->id;
        // Solo el autor, un admin o el dueño del grupo pueden eliminar
        if ($user->id !== $recipe->author_id && !$user->is_admin && !$isOwner) {
            abort(403, 'No tienes permisos para eliminar esta receta.');
        }

        // Eliminar imagen si existe
        if ($recipe->image) {
            Storage::disk('public')->delete($recipe->image);
        }

        $recipe->delete();

        return redirect()->route('recipes.index')->with('success', 'Receta eliminada exitosamente.');
    }
}
