<?php

namespace App\Handler\Product;

use App\DTO\Product\DeleteProductCommand;
use App\Handler\BaseHandler;

class DeleteProductHandler extends BaseHandler
{
    /**
     * @param DeleteProductCommand $command
     */
    protected function handleCommand($command)
    {
        $command->product->delete();
    }
}
