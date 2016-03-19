<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Post;
use App\Category;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // https://gist.github.com/isimmons/8202227
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        Category::truncate();
        Post::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $categories = factory(App\Category::class, 30)->create();

        factory(App\User::class, 10)->create()->each(function($user) use ($categories) {
            $user->posts()->saveMany(factory(App\Post::class, 50)->create()->each(function($post) use ($categories) {
                $post->categories()->sync($categories->random(3)->pluck('id')->all());
            }));
        });

        Model::reguard();
    }
}
