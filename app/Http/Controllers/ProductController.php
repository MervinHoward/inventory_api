<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fields = ['id', 'name', 'description', 'purchasing_price', 'selling_price', 'purchasing_unit_id', 'selling_unit_id'];
        $products = $this->productService->getAll($fields);
        return response()->json(ProductResource::collection($products));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $product = $this->productService->create($request->validated());
        return response()->json(new ProductResource($product), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            $fields = ['id', 'name', 'description', 'purchasing_price', 'selling_price', 'purchasing_unit_id', 'selling_unit_id'];
            $product = $this->productService->getById($id, $fields);
            return response()->json(new ProductResource($product));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Product not found.'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $id, ProductRequest $request)
    {
        try {
            $product = $this->productService->update($id, $request->validated());
            return response()->json(new ProductResource($product));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Product not found.'
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            $this->productService->delete($id);
            return response()->json([
                'message' => 'Product deleted successfully.'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Product not found.'
            ], 404);
        }
    }
}
