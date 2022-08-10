<?php

namespace App\DTO\Product;

use Spatie\DataTransferObject\DataTransferObject;

class CreateProductCommand extends DataTransferObject
{
    public string $title;
    public string $description;
    public float $price;
    public bool $isPublished;
    public array $categories;
}
