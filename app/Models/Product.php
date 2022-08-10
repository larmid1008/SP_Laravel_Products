<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'price',
        'published_at',
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    public function getFilteredList(?string $search): QueryBuilder
    {
        return QueryBuilder::for($this)
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
                ->defaultSort('id', 'title');
    }
}
