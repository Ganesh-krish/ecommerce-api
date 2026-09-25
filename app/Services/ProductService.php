<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{

    public function getProducts(array $filters)
    {

        $query = Product::with('category');

        if (isset($filters['category_id'])) {
            $query->category($filters['category_id']);
        }

        if (isset($filters['min_price'])) {
            $query->minPrice($filters['min_price']);
        }

        if (isset($filters['max_price'])) {
            $query->maxPrice($filters['max_price']);
        }

        if (isset($filters['search'])) {
            $query->search($filters['search']);
        }

        if (isset($filters['min_rating'])) {
            $query->minRating($filters['min_rating']);
        }

        if (isset($filters['in_stock'])) {
            $query->inStock($filters['in_stock']);
        }

        if (isset($filters['sort'])) {
            $query->sortBy($filters['sort']);
        }

        return $query->paginate(10);


        // Category filter
        // $query->when(
        //     $request->has('category_id'),
        //     function ($query) use ($request) {
        //         $query->category(
        //             $request->query('category_id')
        //         );
        //     }
        // );

        // //Minimum price filter
        // $query->when(
        //     $request->has('min_price'),
        //     function ($query) use ($request) {
        //         $query->minPrice(
        //             $request->query('min_price')
        //         );
        //     }
        // );

        // //Maximum price filter
        // $query->when(
        //     $request->has('max_price'),
        //     function ($query) use ($request) {
        //         $query->maxPrice(
        //             $request->query('max_price')
        //         );
        //     }
        // );
        
        // // Search by product name
        // $query->when(
        //     $request->has('search'),
        //     function ($query) use ($request) {
        //         $query->search(
        //           $request->query('search')
        //         );
        //     }
        // );
        
        // // min-rate filtering 
        // $query->when(
        //     $request->has('min_rating'),
        //     function ($query) use ($request) {
        //         $query->minRating(
        //             $request->query('min_rating')
        //         );
        //     }
        // );

        // // stock filtering
        // $query->when(
        //     $request->has('in_stock'),
        //     function ($query) use ($request) {
        //         $query->inStock( $request->query('in_stock'));
        //     }
        // );

        // $query->when(
        //     $request->has('sort'),
        //     function ($query) use ($request) {

        //         $sort = $request->query('sort');

        //         if ($sort === 'price_asc') {
        //             $query->orderBy('price', 'asc');
        //         }

        //         if ($sort === 'price_desc') {
        //             $query->orderBy('price', 'desc');
        //         }

        //         if ($sort === 'name_asc') {
        //             $query->orderBy('name', 'asc');
        //         }

        //         if ($sort === 'name_desc') {
        //             $query->orderBy('name', 'desc');
        //         }
        //     }
        // );


        // $query->when(
        //     $request->has('sort'),
        //     function ($query) use ($request) {
        //         $query->sortBy(
        //             $request->query('sort')
        //         );
        //     }
        // );

    }

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function update(Product $product, array $data)
    {
        $product->update($data);

        return $product->fresh();
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }

    public function find(Product $product)
    {
        return $product->load('category');
    }
}