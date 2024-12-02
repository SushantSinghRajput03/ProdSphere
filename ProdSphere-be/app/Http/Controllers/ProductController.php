<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProductController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        try {
            $request->validate([
                'search' => 'nullable|string',
                'category' => 'nullable|string',
                'min_price' => 'nullable|numeric|min:0',
                'max_price' => 'nullable|numeric|min:0',
                'sort_by' => 'nullable|string|in:name,price,created_at',
                'sort_order' => 'nullable|string|in:asc,desc'
            ]);

            $query = Product::query()
                ->select(['id', 'name', 'price','description', 'category', 'created_at', 'image', 'stock']);

            // Apply filters
            if ($request->has('category')) {
                $query->where('category', '=', $request->category);
            }
            if ($request->has('search')) {
                $query->where('name', 'like', "%{$request->search}%");
            }
            if ($request->has('min_price')) {
                $query->where('price', '>=', (float) $request->min_price);
            }
            if ($request->has('max_price')) {
                $query->where('price', '<=', (float) $request->max_price);
            }

            // Sort
            $sortField = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortField, $sortOrder);

            // Optimize pagination by limiting the number of items
            $perPage = min($request->get('per_page', 10), 50); // Limit maximum items per page
            $products = $query->paginate($perPage);
            
            return response()->json([
                "status" => "success", 
                "message" => "Products fetched successfully", 
                "data" => $products
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(["status" => "error", "message" => "An error occurred", "error" => $th->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'category' => 'required|string',
                'stock' => 'required|integer|min:0',
                'image' => 'required|image|max:2048'
            ]);

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $name = (time() * time()) . '.' . $image->getClientOriginalExtension();
                $path = Storage::disk('public')->putFileAs('products', $image, $name);
                $validated['image'] = $name;
            }

            $validated['user_id'] = $request->user()->id;
            Product::create($validated);
            return response()->json(["status" => "success", "message" => "Product created successfully"], 201);
        } catch (\Throwable $th) {
            Log::error('Product creation failed: ' . $th->getMessage());
            return response()->json(["status" => "error", "message" => "An error occurred", "error" => $th->getMessage()], 500);
        }
    }

    public function update(Request $request, Product $product)
    {
        try {
            $this->authorize('update', $product);

            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'description' => 'sometimes|string',
                'price' => 'sometimes|numeric|min:0',
                'category' => 'sometimes|string',
                'stock' => 'sometimes|integer|min:0',
                'image' => 'nullable|image|max:2048'
            ]);

            if ($request->hasFile('image')) {
                if ($product->image) {
                    Storage::disk('public')->delete("products/{$product->image}");
                }
                
                $image = $request->file('image');
                $name = (time() * time()) . '.' . $image->getClientOriginalExtension();
                Storage::disk('public')->putFileAs('products', $image, $name);
                $validated['image'] = $name;
            }

            $product->update($validated);
            return response()->json(["status" => "success", "message" => "Product updated successfully"], 200);
        } catch (\Throwable $th) {
            Log::error('Product update failed: ' . $th->getMessage());
            return response()->json(["status" => "error", "message" => "An error occurred", "error" => $th->getMessage()], 500);
        }
    }
    public function destroy(Product $product)
    {
        try {
            $this->authorize('delete', $product);

            if ($product->image) {
                Storage::disk('public')->delete("products/{$product->image}");
            }
            $product->delete();
            return response()->json(["status" => "success", "message" => "Product deleted successfully"], 200);
        } catch (\Throwable $th) {
            Log::error('Product deletion failed: ' . $th->getMessage()); // Added error logging
            return response()->json(["status" => "error", "message" => "An error occurred", "error" => $th->getMessage()], 500);
        }
    }
}
