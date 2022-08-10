<?php

namespace App\DTO\Product;

use App\Models\Product;
use Spatie\DataTransferObject\DataTransferObject;

class UpdateProductCommand extends DataTransferObject
{
    public Product $product;
    public string $title;
    public string $description;
    public float $price;
    public bool $isPublished;
    public array $categories;
}
