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
        $category->name = 'Category One';
        $category->parent_id = null;
        $category->created_by = 1;
        $category->status = 'true';
        $category->save();

        $category = new Category();
        $category->name = 'Category Two';
        $category->parent_id = null;
        $category->created_by = 1;
        $category->status = 'true';
        $category->save();

        $category = new Category();
        $category->name = 'Category Three';
        $category->parent_id = null;
        $category->created_by = 1;
        $category->status = 'true';
        $category->save();

        $category = new Category();
        $category->name = 'Category Four';
        $category->parent_id = null;
        $category->created_by = 1;
        $category->status = 'true';
        $category->save();

        $category = new Category();
        $category->name = 'Category Five';
        $category->parent_id = null;
        $category->created_by = 1;
        $category->status = 'true';
        $category->save();
    }
}
