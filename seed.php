<?php

use App\Models\Product;
use App\Models\Category;

require 'bootstrap.php';

for ($i = 1; $i <= 3; $i++) {
    $category = Category::make()->create([
        'name' => 'Category 1'
    ]);

    for ($j = 1; $j <= 2; $j++) {
        /** 
         * @var Product
         */
        $product = Product::make()->create([
            'name' => 'Product ' . $i * $j,
            'category_id' => $category->id
        ]);

        $img_url = $product->genImageUrl('png');

        copy(__DIR__ . '/default.png', ROOT_DIR . $img_url);

        $product->fill(['image_url' => $img_url])->save();
    }
}
