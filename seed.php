<?php

use App\Models\Product;
use App\Models\Category;

require 'bootstrap.php';

$c = 1;
for ($i = 1; $i <= 3; $i++) {
    $category = Category::make()->create([
        'name' => 'Category ' . $i
    ]);

    for ($j = 1; $j <= 2; $j++) {
        /** 
         * @var Product
         */
        $product = Product::make()->create([
            'name' => 'Product ' . $c,
            'category_id' => $category->id
        ]);
        $c++;

        $img_url = $product->genImageUrl('png');

        copy(__DIR__ . '/default.png', ROOT_DIR . $img_url);

        $product->fill(['image_url' => $img_url])->save();
    }
}
