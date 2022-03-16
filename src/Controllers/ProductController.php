<?php

namespace App\Controllers;

use App\Core\Request;
use App\Models\Category;
use App\Models\Product;

class ProductController
{
    public function index()
    {
        $search = Request::get('search');
        $data = [
            'categories' => Category::make()->all(),
            'search' => $search,
        ];

        if ($search) {
            $data['products'] = Product::make()->paginate('SELECT `products`.`id`, `products`.`name` AS name, `category_id`, `categories`.`name` AS `category_name`, `image_url`  FROM `products` INNER JOIN `categories` ON `products`.`category_id` = `categories`.`id` WHERE `products`.`name` LIKE ? OR `categories`.`name` LIKE ?', [
                '%' . $search . '%',
                '%' . $search . '%',
            ], 5);
        } else {
            $data['products'] = Product::make()->paginate('SELECT `products`.`id`, `products`.`name` as name, `category_id`, `categories`.`name` as `category_name`, `image_url` FROM `products` INNER JOIN `categories` ON `products`.`category_id` = `categories`.`id`', [], 5);
        }

        return view('product/index', $data);
    }

    public function create()
    {
        $this->validateInput();

        /**
         * @var \App\Models\Product
         */
        $product = Product::make()->create([
            'name' => Request::get('name'),
            'category_id' => Request::get('category_id'),
        ]);
        $imageUrl = $product->genImageUrl(pathinfo($_FILES["image"]["name"])['extension']);
        $destination = ROOT_DIR . $imageUrl;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
            $_SESSION['_flash']['message'] = "Created product " . Request::get('name');
            $_SESSION['_flash']['status'] = 'success';
        } else {
            $_SESSION['_flash']['message'] = "Unable to upload image.";
            $_SESSION['_flash']['status'] = 'danger';
        }
        $product->fill([
            'image_url' => $imageUrl,
        ])->save();
        redirect('/products');
    }

    public function update()
    {
        $this->validateInput(false);
        /**
         * @var \App\Models\Product|null
         */
        $product = Product::make()->find(Request::get('product_id'));

        if (!$product) {
            return redirect('/products');
        }

        if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image_url = $product->genImageUrl(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
            unlink(ROOT_DIR . $product->image_url);
            move_uploaded_file($_FILES['image']['tmp_name'], ROOT_DIR . $image_url);
            $product->fill([
                'name' => Request::get('name'),
                'category_id' => Request::get('category_id'),
                'image_url' => $image_url
            ])->save();
        } else {
            $product->fill([
                'name' => Request::get('name'),
                'category_id' => Request::get('category_id'),
            ])->save();
        }

        $_SESSION['_flash']['message'] = "Update product $product->id successfully.";
        $_SESSION['_flash']['status'] = 'success';

        redirect('/products');
    }

    public function destroy()
    {
        $product = Product::make()->find(Request::get('product_id'));
        if (!$product) {
            return redirect('/products');
        }

        unlink(ROOT_DIR . $product->image_url);
        $product->delete();

        $_SESSION['_flash']['message'] = "Delete product " . Request::get('product_id') . " successfully.";
        $_SESSION['_flash']['status'] = 'success';

        redirect('/products');
    }

    public function copy()
    {
        $product = Product::make()->find(Request::get('product_id'));
        if (!$product) {
            return redirect('/products');
        }
        /**
         * @var \App\Models\Product
         */
        $newProduct = Product::make()->create([
            'name' => $product->name,
            'category_id' => $product->category_id,
        ]);

        $image_url = $newProduct->genImageUrl(pathinfo($product->image_url)['extension']);
        copy(ROOT_DIR . $product->image_url, ROOT_DIR . $image_url);

        $newProduct->image_url = $image_url;
        $newProduct->save();

        $_SESSION['_flash']['message'] = "Copy product $product->id to $newProduct->id successfully.";
        $_SESSION['_flash']['status'] = 'success';

        redirect('/products');
    }

    private function validateInput(bool $requiredImage = true)
    {
        $category_id = Request::get('category_id');
        $name = Request::get('name');
        $category = Category::make()->find($category_id);
        if (!$category) {
            $_SESSION['_flash']['message'] = 'Invalid category.';
            $_SESSION['_flash']['status'] = 'danger';
            redirect('/products');
        }

        if (!$name) {
            $_SESSION['_flash']['message'] = 'Product name is required.';
            $_SESSION['_flash']['status'] = 'danger';
            redirect('/products');
        }

        if ($requiredImage && (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK)) {
            $_SESSION['_flash']['message'] = 'Please uploade an image.';
            $_SESSION['_flash']['status'] = 'danger';
            redirect('/products');
        }
    }
}
