<?php

namespace App\Handler\Category;

use App\DTO\Category\CreateCategoryCommand;
use App\Handler\BaseHandler;
use App\Models\Category;

class CreateCategoryHandler extends BaseHandler
{
    /**
     * @param CreateCategoryCommand $command
     */
    protected function handleCommand($command)
    {
        return Category::create([
            'title' => $command->title
        ]);
    }
}
