<?php

namespace App\Models;

class Product extends AbstractModel
{
    public function getTableName(): string
    {
        return 'products';
    }

    public function genImageUrl(string $imageExt): string
    {
        return "/public/images/" . $this->id . '.' . $imageExt;
    }
}
