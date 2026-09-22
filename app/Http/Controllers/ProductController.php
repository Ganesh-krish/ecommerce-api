<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

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
                $query->where(
                    'category_id',
                    $request->query('category_id')
                );
            }
        );

        //Minimum price filter
        $query->when(
            $request->has('min_price'),
            function ($query) use ($request) {
                $query->where(
                    'price',
                    '>=',
                    $request->query('min_price')
                );
            }
        );

        //Maximum price filter
        $query->when(
            $request->has('max_price'),
            function ($query) use ($request) {
                $query->where(
                    'price',
                    '<=',
                    $request->query('max_price')
                );
            }
        );
        
        // Search by product name
        $query->when(
            $request->has('search'),
            function ($query) use ($request) {
                $query->where(
                    'name',
                    'like',
                    '%' . $request->query('search') . '%'
                );
            }
        );
        
        // min-rate filtering 
        $query->when(
            $request->has('min_rating'),
            function ($query) use ($request) {
                $query->where(
                    'rating',
                    '>=',
                    $request->query('min_rating')
                );
            }
        );

        // stock filtering
        $query->when(
            $request->has('in_stock'),
            function ($query) use ($request) {

                if ($request->query('in_stock') == 1) {
                    $query->where('stock', '>', 0);
                }

                if ($request->query('in_stock') == 0) {
                    $query->where('stock', '=', 0);
                }
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

                $sortOptions = [
                    'price_asc'  => ['price', 'asc'],
                    'price_desc' => ['price', 'desc'],
                    'name_asc'   => ['name', 'asc'],
                    'name_desc'  => ['name', 'desc'],
                ];

                $sort = $request->query('sort');

                if (isset($sortOptions[$sort])) {
                    $query->orderBy(
                        $sortOptions[$sort][0],
                        $sortOptions[$sort][1]
                    );
                }
            }
        );
        $products = $query->paginate(10);

        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'rating' => 'nullable|numeric|min:0|max:5',
            'image' => 'nullable|string|max:255',
        ]);

        $product = Product::create($validated);

        return response()->json(
            $product->load('category'),
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return response()->json($product->load('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'rating' => 'nullable|numeric|min:0|max:5',
            'image' => 'nullable|string|max:255',
        ]);

        $product->update($validated);

        return response()->json(
            $product->load('category')
        );
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
