<?php

namespace App\Http\Controllers;

use App\DTO\Product\CreateProductCommand;
use App\Handler\Product\CreateProductHandler;
use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Resources\Product\ProductFullResource;
use App\Models\Product;
use Illuminate\Http\Request;
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
