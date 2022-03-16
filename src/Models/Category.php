<?php

namespace App\Models;

class Category extends AbstractModel
{
    public function getTableName(): string
    {
        return 'categories';
    }
}
