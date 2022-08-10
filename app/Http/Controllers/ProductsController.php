<?php

namespace App\Http\Controllers;

use App\Http\Resources\Product\ProductFullResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

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
            QueryBuilder::for(Product::class)
                ->with('categories')
                ->withTrashed()
                ->when($search, function (Builder $builder) use ($search) {
                    $builder->where('title',  'ilike', "%$search%");
                })
                ->allowedFilters([
                    AllowedFilter::callback('category_id', function (Builder $builder, $value) {
                        $builder->whereHas('categories', function (Builder $builder) use ($value) {
                            $builder->where('categories.id', $value);
                        });
                    }),
                    AllowedFilter::callback('category_title', function (Builder $builder, $value) {
                        $builder->whereHas('categories', function (Builder $builder) use ($value) {
                            $builder->where('categories.title', 'ilike', "%$value%");
                        });
                    }),
                    AllowedFilter::callback('price_start', function (Builder $builder, $value) {
                        $builder->where('products.price', '>=', $value);
                    }),
                    AllowedFilter::callback('price_end', function (Builder $builder, $value) {
                        $builder->where('products.price', '<=', $value);
                    }),
                    AllowedFilter::callback('is_published', function (Builder $builder, $value) {
                        $builder->when(
                            $value,
                            function (Builder $builder) {
                                $builder->whereNotNull('products.published_at');
                            },
                            function (Builder $builder) {
                                $builder->whereNull('products.published_at');
                            },
                        );
                    }),
                    AllowedFilter::callback('trashed', function (Builder $builder, $value) {
                        $builder->when(
                            $value,
                            function (Builder $builder) {
                                $builder->whereNotNull('products.deleted_at');
                            },
                            function (Builder $builder) {
                                $builder->whereNull('products.deleted_at');
                            },
                        );
                    }),
                ])
                ->defaultSort('id', 'title')
                ->paginate()
                ->appends(request()->query())
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
