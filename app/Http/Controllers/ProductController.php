<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Resources\ProductResource;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Category filter
        $query->when(
            $request->has('category_id'),
            function ($query) use ($request) {
                $query->category(
                    $request->query('category_id')
                );
            }
        );

        //Minimum price filter
        $query->when(
            $request->has('min_price'),
            function ($query) use ($request) {
                $query->minPrice(
                    $request->query('min_price')
                );
            }
        );

        //Maximum price filter
        $query->when(
            $request->has('max_price'),
            function ($query) use ($request) {
                $query->maxPrice(
                    $request->query('max_price')
                );
            }
        );
        
        // Search by product name
        $query->when(
            $request->has('search'),
            function ($query) use ($request) {
                $query->search(
                  $request->query('search')
                );
            }
        );
        
        // min-rate filtering 
        $query->when(
            $request->has('min_rating'),
            function ($query) use ($request) {
                $query->minRating(
                    $request->query('min_rating')
                );
            }
        );

        // stock filtering
        $query->when(
            $request->has('in_stock'),
            function ($query) use ($request) {
                $query->inStock( $request->query('in_stock'));
            }
        );

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


        $query->when(
            $request->has('sort'),
            function ($query) use ($request) {
                $query->sortBy(
                    $request->query('sort')
                );
            }
        );
        $products = $query->paginate(10);

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest  $request)
    {
        // $validated = $request->validate([
        //     'category_id' => 'required|exists:categories,id',
        //     'name' => 'required|string|max:255',
        //     'description' => 'nullable|string',
        //     'price' => 'required|numeric|min:0',
        //     'stock' => 'required|integer|min:0',
        //     'rating' => 'nullable|numeric|min:0|max:5',
        //     'image' => 'nullable|string|max:255',
        // ]);

        $product = Product::create($request->validated());

        $product->load('category');
        
        return (new ProductResource($product))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load('category');

        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request,Product $product)
    {
      

        $product->update( $request->validated());

        $product->load('category');
        return new ProductResource($product);
            
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully'
        ]);
    }
}
