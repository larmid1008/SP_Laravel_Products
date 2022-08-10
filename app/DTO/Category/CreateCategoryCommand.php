<?php

namespace App\DTO\Category;

use Spatie\DataTransferObject\DataTransferObject;

class CreateCategoryCommand extends DataTransferObject
{
    public string $title;
}
