<?php

namespace App\DTO\Product;

use App\Models\Product;
use Spatie\DataTransferObject\DataTransferObject;

class DeleteProductCommand extends DataTransferObject
{
    public Product $product;
}
