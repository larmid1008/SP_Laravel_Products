<?php

namespace App\DTO\Category;

use Spatie\DataTransferObject\DataTransferObject;

class DeleteCategoryCommand extends DataTransferObject
{
    public int $categoryId;
}
