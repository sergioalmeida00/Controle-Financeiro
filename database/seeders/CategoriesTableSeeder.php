<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

        $categories = [
            ['name' => 'Salário', 'icon' => 'travel', 'type' => 'INCOME'],
            ['name' => 'Educação', 'icon' => 'education', 'type' => 'EXPENSE'],
            ['name' => 'Outro', 'icon' => 'other', 'type' => 'INCOME'],
            ['name' => 'Casa', 'icon' => 'home', 'type' => 'EXPENSE'],
            ['name' => 'Outro', 'icon' => 'other', 'type' => 'EXPENSE'],
            ['name' => 'Freelance', 'icon' => 'freelance', 'type' => 'INCOME'],
            ['name' => 'Transporte', 'icon' => 'transport', 'type' => 'EXPENSE'],
            ['name' => 'Viagem', 'icon' => 'travel', 'type' => 'EXPENSE'],
            ['name' => 'Lazer', 'icon' => 'fun', 'type' => 'EXPENSE'],
            ['name' => 'Salário', 'icon' => 'salary', 'type' => 'INCOME'],
            ['name' => 'Mercado', 'icon' => 'grocery', 'type' => 'EXPENSE'],
            ['name' => 'Alimentação', 'icon' => 'food', 'type' => 'EXPENSE'],
            ['name' => 'Roupas', 'icon' => 'clothes', 'type' => 'EXPENSE'],
        ];

        foreach ($users as $user) {
            if ($user->categories()->count() == 0) {
                foreach ($categories as  $category) {
                    $category['user_id'] = $user->id;
                    Category::create($category);
                }
            }
        }
    }
}
