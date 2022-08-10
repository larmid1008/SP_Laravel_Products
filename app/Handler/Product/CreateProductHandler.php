<?php

namespace App\Handler\Product;

use App\DTO\Product\CreateProductCommand;
use App\Handler\BaseHandler;
use App\Models\Category;
use App\Models\Product;

class CreateProductHandler extends BaseHandler
{
    /**
     * @param CreateProductCommand $command
     */
    protected function handleCommand($command)
    {
        $categories = Category::whereIn('id', $command->categories)
            ->get();
        $categoriesIds = $categories->pluck('id')->toArray();

        if (!empty(array_diff($command->categories, $categoriesIds))) {
            return abort('404', 'Some categories are doesnt exist');
        }

        if (count($categoriesIds) < 2 || count($categoriesIds) > 10) {
            throw new \Exception(
                'You must specify at least 2 and no more than 10 categories',
                403
            );
        }

        $product = Product::create([
            'title' => $command->title,
            'description' => $command->description,
            'price' => $command->price,
            'published_at' => $command->isPublished ? now() : null,
        ]);

        $product->categories()->attach($categories);

        return $product->load('categories');
    }
}
