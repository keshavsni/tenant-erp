<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateWarehouseStockRequest;
use App\Http\Requests\WarehouseRequest;
use App\Http\Resources\WarehouseResource;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\WarehouseProduct;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class WareHouseController extends Controller
{
    use ApiResponse;

    public function index()
    {
        return WarehouseResource::collection(
            Warehouse::where(
                'company_id',
                auth()->user()->company_id
            )->paginate()
        );
    }

    public function store(
        WarehouseRequest $request
    ) {

        $warehouse = Warehouse::create([
            ...$request->validated(),
            'company_id' => auth()->user()->company_id
        ]);

        return $this->success(
            new WarehouseResource($warehouse),
            'Warehouse Created',
            201
        );
    }

    public function update(
        WarehouseRequest $request,
        Warehouse $warehouse
    ) {
        if (
            $warehouse->company_id !== auth()->user()->company_id
        ) {
            return $this->error(
                'Unauthorized access',
                null,
                403
            );
        }

        $warehouse->update(
            $request->validated()
        );

        return $this->success(
            new WarehouseResource($warehouse),
            'Warehouse updated successfully'
        );
    }

    public function destroy(
        Warehouse $warehouse
    ) {
        if (
            $warehouse->company_id !== auth()->user()->company_id
        ) {
            return $this->error(
                'Unauthorized access',
                null,
                403
            );
        }

        $warehouse->delete();

        return $this->success(
            [],
            'Warehouse deleted successfully'
        );
    }



    public function updateStock(
        UpdateWarehouseStockRequest $request,
        Warehouse $warehouse
    ) {

        // Tenant validation
        if (
            $warehouse->company_id !== auth()->user()->company_id
        ) {
            return $this->error(
                'Unauthorized warehouse access',
                null,
                403
            );
        }

        $product = Product::where(
            'company_id',
            auth()->user()->company_id
        )
            ->findOrFail(
                $request->product_id
            );

        $warehouseProduct =
            WarehouseProduct::firstOrNew([
                'warehouse_id' => $warehouse->id,
                'product_id' => $product->id,
            ]);

        $warehouseProduct->stock =
            $request->stock;

        $warehouseProduct->save();

        return $this->success([
            'warehouse_id' => $warehouse->id,
            'product_id' => $product->id,
            'stock' => $warehouseProduct->stock
        ], 'Stock updated successfully');
    }
}
