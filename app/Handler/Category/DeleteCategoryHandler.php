<?php

namespace App\Handler\Category;

use App\DTO\Category\DeleteCategoryCommand;
use App\Handler\BaseHandler;
use App\Models\Category;

class DeleteCategoryHandler extends BaseHandler
{
    /**
     * @param DeleteCategoryCommand $command
     * @throws \Exception
     */
    protected function handleCommand($command)
    {
        $category = Category::withCount('products')
            ->findOrFail($command->categoryId);

        if ($category->products_count) {
            throw new \Exception('You cant delete category with products', 403);
        }

        $category->delete();
    }
}
