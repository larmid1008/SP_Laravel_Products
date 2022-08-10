<?php

namespace App\Http\Controllers;

use App\DTO\Category\CreateCategoryCommand;
use App\DTO\Category\DeleteCategoryCommand;
use App\Handler\Category\CreateCategoryHandler;
use App\Handler\Category\DeleteCategoryHandler;
use App\Http\Requests\Category\CreateCategoryRequest;
use App\Http\Resources\Category\CategorySmallResource;
use Illuminate\Http\Response;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class CategoriesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param CreateCategoryRequest $request
     * @param CreateCategoryHandler $handler
     * @return CategorySmallResource
     * @throws UnknownProperties
     */
    public function store(CreateCategoryRequest $request, CreateCategoryHandler $handler)
    {
        $command = new CreateCategoryCommand(
            title: $request->get('title')
        );

        return new CategorySmallResource($handler->handle($command));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @param DeleteCategoryHandler $handler
     * @return Response
     * @throws UnknownProperties
     */
    public function destroy(int $id, DeleteCategoryHandler $handler)
    {
        $handler->handle(new DeleteCategoryCommand(
            categoryId: $id
        ));

        return response()->noContent();
    }
}
