<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = new Category();
        $category->name = 'Category A';
        $category->parent_id = null;
        $category->created_by = 1;
        $category->status = 'true';
        $category->save();

        $category = new Category();
        $category->name = 'Category B';
        $category->parent_id = null;
        $category->created_by = 1;
        $category->status = 'true';
        $category->save();

        $category = new Category();
        $category->name = 'Category C';
        $category->parent_id = null;
        $category->created_by = 1;
        $category->status = 'true';
        $category->save();

        $category = new Category();
        $category->name = 'Category D';
        $category->parent_id = null;
        $category->created_by = 1;
        $category->status = 'true';
        $category->save();

        $category = new Category();
        $category->name = 'Category E';
        $category->parent_id = null;
        $category->created_by = 1;
        $category->status = 'true';
        $category->save();
    }
}
