<?php

namespace Database\Seeders;

use App\Models\Recipe;
use App\Models\User;
use App\Models\Group;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $recipes = [
            [
                'title' => 'Tarta de Manzana',
                'description' => 'Una tarta clásica con manzanas frescas y canela.',
                'ingredients' => [
                    ['name' => 'Manzanas', 'quantity' => '3', 'unit' => 'unidades'],
                    ['name' => 'Harina', 'quantity' => '200', 'unit' => 'g'],
                    ['name' => 'Azúcar', 'quantity' => '100', 'unit' => 'g'],
                    ['name' => 'Canela', 'quantity' => '1', 'unit' => 'cucharadita'],
                ],
                'steps' => [
                    ['number' => 1, 'description' => 'Pelar y cortar las manzanas.'],
                    ['number' => 2, 'description' => 'Mezclar con harina, azúcar y canela.'],
                    ['number' => 3, 'description' => 'Hornear a 180°C por 40 minutos.'],
                ],
                'prep_time' => 20,
                'cook_time' => 40,
                'servings' => 6,
                'difficulty' => 'Fácil',
                'category' => 'Postre',
                'tags' => ['dulce', 'fruta', 'clásico'],
                'image' => null,
                'author_id' => User::where('username', 'nicosmico')->first()->id,
                'group_id' => Group::where('name', 'Familia López')->first()->id,
                'is_private' => false,
                'rating' => 4.5,
                'rating_count' => 10,
            ],
            [
                'title' => 'Ensalada Primavera',
                'description' => 'Ensalada fresca con vegetales de estación.',
                'ingredients' => [
                    ['name' => 'Lechuga', 'quantity' => '1', 'unit' => 'unidad'],
                    ['name' => 'Tomate', 'quantity' => '2', 'unit' => 'unidades'],
                    ['name' => 'Zanahoria', 'quantity' => '1', 'unit' => 'unidad'],
                ],
                'steps' => [
                    ['number' => 1, 'description' => 'Lavar y cortar los vegetales.'],
                    ['number' => 2, 'description' => 'Mezclar en un bol y servir.'],
                ],
                'prep_time' => 10,
                'cook_time' => 0,
                'servings' => 2,
                'difficulty' => 'Fácil',
                'category' => 'Ensalada',
                'tags' => ['saludable', 'vegetariano'],
                'image' => null,
                'author_id' => User::where('username', 'marior')->first()->id,
                'group_id' => Group::where('name', 'Cocina Saludable')->first()->id,
                'is_private' => false,
                'rating' => 4.0,
                'rating_count' => 5,
            ],
            [
                'title' => 'Milanesas de Berenjena',
                'description' => 'Milanesas crocantes de berenjena, ideales para una comida liviana.',
                'ingredients' => [
                    ['name' => 'Berenjenas', 'quantity' => '2', 'unit' => 'unidades'],
                    ['name' => 'Pan rallado', 'quantity' => '150', 'unit' => 'g'],
                    ['name' => 'Huevos', 'quantity' => '2', 'unit' => 'unidades'],
                    ['name' => 'Sal', 'quantity' => 'a gusto', 'unit' => ''],
                ],
                'steps' => [
                    ['number' => 1, 'description' => 'Cortar las berenjenas en rodajas y salar.'],
                    ['number' => 2, 'description' => 'Pasar por huevo batido y pan rallado.'],
                    ['number' => 3, 'description' => 'Freír hasta dorar.'],
                ],
                'prep_time' => 15,
                'cook_time' => 15,
                'servings' => 4,
                'difficulty' => 'Fácil',
                'category' => 'Plato Principal',
                'tags' => ['vegetariano', 'liviano'],
                'image' => null,
                'author_id' => User::where('username', 'nicosmico')->first()->id,
                'group_id' => Group::where('name', 'Abuela Ilda')->first()->id,
                'is_private' => false,
                'rating' => 4.2,
                'rating_count' => 8,
            ],
            [
                'title' => 'Guiso de Lentejas',
                'description' => 'Un guiso reconfortante y nutritivo, perfecto para el invierno.',
                'ingredients' => [
                    ['name' => 'Lentejas', 'quantity' => '250', 'unit' => 'g'],
                    ['name' => 'Chorizo', 'quantity' => '1', 'unit' => 'unidad'],
                    ['name' => 'Cebolla', 'quantity' => '1', 'unit' => 'unidad'],
                    ['name' => 'Zanahoria', 'quantity' => '1', 'unit' => 'unidad'],
                ],
                'steps' => [
                    ['number' => 1, 'description' => 'Saltear la cebolla y zanahoria picadas.'],
                    ['number' => 2, 'description' => 'Agregar el chorizo en rodajas y las lentejas.'],
                    ['number' => 3, 'description' => 'Cubrir con agua y cocinar hasta que las lentejas estén tiernas.'],
                ],
                'prep_time' => 20,
                'cook_time' => 60,
                'servings' => 6,
                'difficulty' => 'Media',
                'category' => 'Plato Principal',
                'tags' => ['invierno', 'tradicional'],
                'image' => null,
                'author_id' => User::where('username', 'nicosmico')->first()->id,
                'group_id' => Group::where('name', 'Abuela Ilda')->first()->id,
                'is_private' => false,
                'rating' => 4.7,
                'rating_count' => 12,
            ],
            [
                'title' => 'Budín de Limón',
                'description' => 'Budín húmedo y esponjoso con sabor a limón.',
                'ingredients' => [
                    ['name' => 'Harina', 'quantity' => '200', 'unit' => 'g'],
                    ['name' => 'Azúcar', 'quantity' => '150', 'unit' => 'g'],
                    ['name' => 'Huevos', 'quantity' => '3', 'unit' => 'unidades'],
                    ['name' => 'Limón', 'quantity' => '1', 'unit' => 'unidad'],
                ],
                'steps' => [
                    ['number' => 1, 'description' => 'Batir los huevos con el azúcar.'],
                    ['number' => 2, 'description' => 'Agregar la ralladura y jugo de limón.'],
                    ['number' => 3, 'description' => 'Incorporar la harina y hornear a 180°C por 35 minutos.'],
                ],
                'prep_time' => 15,
                'cook_time' => 35,
                'servings' => 8,
                'difficulty' => 'Fácil',
                'category' => 'Postre',
                'tags' => ['dulce', 'limón'],
                'image' => null,
                'author_id' => User::where('username', 'nicosmico')->first()->id,
                'group_id' => Group::where('name', 'Abuela Ilda')->first()->id,
                'is_private' => false,
                'rating' => 4.8,
                'rating_count' => 15,
            ],
        ];

        foreach ($recipes as $recipe) {
            Recipe::create($recipe);
        }
    }
}
