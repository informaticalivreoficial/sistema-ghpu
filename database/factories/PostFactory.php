<?php

namespace Database\Factories;

use App\Models\CatPost;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition()
    {
        $pai = CatPost::factory()->create();

        $filha = CatPost::factory()->child()->create();

        return [
            'user_id' => User::factory(), 
            'tipo' => 'artigo',

            'titulo' => $this->faker->sentence(),
            'content' => $this->faker->paragraphs(5, true),

            'slug' => $this->faker->unique()->slug(),

            'tags' => implode(', ', $this->faker->words(6)),

            'views' => 0,

            'categoria' => $filha->id,
            'cat_pai'   => $pai->id,

            'status' => 1,
            'comentarios' => 0,

            'thumb_legenda' => $this->faker->sentence(),

            'publish_at' => now(),
        ];
    }
}