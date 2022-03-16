<?php

namespace App\Controllers;

use App\Core\Request;
use App\Models\Category;
use App\Models\Product;

class CategoryController
{
    public function index()
    {
        $search = Request::get('search');
        $data = [
            'categories' => Category::make()->all(),
            'search' => $search,
        ];

        if ($search) {
            $data['categories'] = Category::make()->paginate('SELECT * FROM `categories` WHERE `categories`.`name` LIKE ?', [
                '%' . $search . '%',
            ], 5);
        } else {
            $data['categories'] = Category::make()->paginate('SELECT * from categories', [], 5);
        }
        return view('category/index', $data);
    }

    public function create()
    {
        $this->validateInput();

        $category = Category::make()->create([
            'name' => Request::get('name'),
        ]);

        $_SESSION['_flash']['message'] = "Create $category->name category successfully.";
        $_SESSION['_flash']['status'] = 'success';

        redirect('/categories');
    }

    public function update()
    {
        $this->validateInput();

        $category = Category::make()->find(Request::get('category_id'));

        if (!$category) {
            redirect('/categories');
        }

        $category->fill(['name' => Request::get('name')])->save();

        $_SESSION['_flash']['message'] = "Update $category->id category successfully.";
        $_SESSION['_flash']['status'] = 'success';

        redirect('/categories');
    }

    public function destroy()
    {
        $category = Category::make()->find(Request::get('category_id'));

        if (!$category) {
            redirect('/categories');
        }

        $name = $category->name;

        Product::make()->query('DELETE FROM products where category_id = ?', [
            $category->id
        ]);
        $category->delete();

        $_SESSION['_flash']['message'] = "Deleted $name category and it's products.";
        $_SESSION['_flash']['status'] = 'success';

        redirect('/categories');
    }

    private function validateInput()
    {
        if (!Request::has('name')) {
            $_SESSION['_flash']['message'] = 'Invalid name.';
            $_SESSION['_flash']['status'] = 'error';

            redirect('/categories');
        }
    }
}
