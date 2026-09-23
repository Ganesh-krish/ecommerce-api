<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Category;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'stock',
        'rating',
        'image',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeMinPrice($query,$price){
        return $query->where('price','>=',$price);
    }

    
    public function scopeMaxPrice($query,$price){
        return $query->where('price','<=',$price);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(
            'name',
            'like',
            '%' . $search . '%'
        );
    }

    public function scopeMinRating($query, $rating)
    {
        return $query->where(
            'rating',
            '>=',
            $rating
        );
    }

    public function scopeInStock($query, $value)
    {
        if ((int) $value === 1) {
            return $query->where('stock', '>', 0);
        }

        if ((int) $value === 0) {
            return $query->where('stock', '=', 0);
        }

        return $query;
    }

    public function scopeSortBy($query, $sort)
    {
        $sortOptions = [
            'price_asc'  => ['price', 'asc'],
            'price_desc' => ['price', 'desc'],
            'name_asc'   => ['name', 'asc'],
            'name_desc'  => ['name', 'desc'],
        ];

        if (isset($sortOptions[$sort])) {
            return $query->orderBy(
                $sortOptions[$sort][0],
                $sortOptions[$sort][1]
            );
        }

        return $query;
    }
}
