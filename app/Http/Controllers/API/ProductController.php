<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;

class ProductController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $products = Product::query()

            ->where(
                'company_id',
                auth()->user()->company_id
            )

            ->when($request->search, function ($q) use ($request) {

                $q->where(function ($query) use ($request) {

                    $query->where(
                        'name',
                        'like',
                        "%{$request->search}%"
                    )
                        ->orWhere(
                            'sku',
                            'like',
                            "%{$request->search}%"
                        );
                });
            })

            ->latest()
            ->paginate(
                $request->per_page ?? 10
            );

        return ProductResource::collection(
            $products
        );
    }

    public function store(ProductRequest $request)
    {
        $product = Product::create([
            ...$request->validated(),
            'company_id' => auth()->user()->company_id
        ]);

        return $this->success(
            new ProductResource($product),
            'Product Created',
            201
        );
    }

    public function show(Product $product)
    {
        $this->authorizeProduct($product);

        return $this->success(
            new ProductResource($product)
        );
    }

    public function update(
        ProductRequest $request,
        Product $product
    ) {


        $product->update(
            $request->validated()
        );

        return $this->success(
            new ProductResource($product),
            'Product Updated'
        );
    }

    public function destroy(Product $product)
    {
        $this->authorizeProduct($product);

        $product->delete();

        return $this->success(
            [],
            'Product Deleted'
        );
    }

    private function authorizeProduct(Product $product): void
    {
        if (
            $product->company_id !== auth()->user()->company_id
        ) {
            throw new AuthorizationException(
                'You are not authorized to access this product.'
            );
        }
    }
}
