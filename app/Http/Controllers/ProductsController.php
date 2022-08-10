<?php

namespace App\Http\Controllers;

use App\DTO\Product\CreateProductCommand;
use App\DTO\Product\DeleteProductCommand;
use App\DTO\Product\UpdateProductCommand;
use App\Handler\Product\CreateProductHandler;
use App\Handler\Product\DeleteProductHandler;
use App\Handler\Product\UpdateProductHandler;
use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Resources\Product\ProductFullResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        return ProductFullResource::collection(
            (new Product)
                ->getFilteredList($search)
                ->paginate()
                ->appends(request()->query())
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateProductRequest $request
     * @param CreateProductHandler $handler
     * @return ProductFullResource
     * @throws UnknownProperties
     */
    public function store(CreateProductRequest $request, CreateProductHandler $handler)
    {
        $command = new CreateProductCommand(
            title: $request->get('title'),
            description: $request->get('description'),
            price: $request->get('price'),
            isPublished: $request->get('is_published'),
            categories: $request->get('categories'),
        );

        return new ProductFullResource($handler->handle($command));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CreateProductRequest $request
     * @param Product $product
     * @param UpdateProductHandler $handler
     * @return ProductFullResource
     * @throws UnknownProperties
     */
    public function update(CreateProductRequest $request, Product $product, UpdateProductHandler $handler)
    {
        $command = new UpdateProductCommand(
            product: $product,
            title: $request->get('title'),
            description: $request->get('description'),
            price: $request->get('price'),
            isPublished: $request->get('is_published'),
            categories: $request->get('categories'),
        );

        return new ProductFullResource($handler->handle($command));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     * @param DeleteProductHandler $handler
     * @return Response
     * @throws UnknownProperties
     */
    public function destroy(Product $product, DeleteProductHandler $handler)
    {
        $handler->handle(
            new DeleteProductCommand(
                product: $product,
            )
        );

        return response()->noContent();
    }
}
