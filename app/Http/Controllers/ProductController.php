<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductController extends Controller
{
    public function index()
    {
        try {
            $products = Product::paginate(10);
            return response()->json([
                'success' => true,
                'message' => 'Products fetched successfully.',
                'data' => ProductResource::collection($products)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch products.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(StoreProductRequest $request)
    {
        try {
            $product = Product::create($request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Product created successfully.',
                'data' => new ProductResource($product)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create product.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $product = Product::findOrFail($id);
            return response()->json([
                'success' => true,
                'message' => 'Product fetched successfully.',
                'data' => new ProductResource($product)
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch product.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(UpdateProductRequest $request, $id)
    {
        try {
            $product = Product::find($id);
            if ($product) {
                $product->update($request->validated());
                return response()->json([
                    'success' => true,
                    'message' => 'Product updated successfully.',
                    'data' => new ProductResource($product)
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found.',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update product.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $product = Product::find($id);
            if ($product) {
                $product->delete();
                return response()->json([
                    'success' => true,
                    'message' => 'Product deleted successfully.'
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found.',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete product.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
