<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Jobs\SendOrderConfirmationJob;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\WarehouseProduct;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $orders = Order::query()
            ->where(
                'company_id',
                auth()->user()->company_id
            )
            ->with([
                'warehouse',
                'items.product'
            ])
            ->latest()
            ->paginate(
                $request->per_page ?? 10
            );

        return $this->success(
            [
                'orders' => OrderResource::collection($orders),
                'pagination' => [
                    'current_page' => $orders->currentPage(),
                    'last_page' => $orders->lastPage(),
                    'per_page' => $orders->perPage(),
                    'total' => $orders->total(),
                ]
            ],
            'Orders fetched successfully'
        );
    }

    public function store(
        OrderRequest $request
    ) {

        $companyId = auth()->user()->company_id;

        $order = DB::transaction(
            function () use ($request, $companyId) {

                $order = Order::create([
                    'company_id' =>
                    auth()->user()->company_id,

                    'warehouse_id' =>
                    $request->warehouse_id,

                    'total_amount' => 0
                ]);

                $total = 0;


                foreach (
                    $request->products
                    as $item
                ) {

                    $product = Product::where('id', $item['product_id'])
                        ->where('company_id', $companyId)->first();

                    if (!$product) {
                        throw ValidationException::withMessages([
                            'product_id' => [
                                "Product {$item['product_id']} does not belong to your company."
                            ]
                        ]);
                    }

                    $warehouseProduct =
                        WarehouseProduct::where(
                            'warehouse_id',
                            $request->warehouse_id
                        )
                        ->where(
                            'product_id',
                            $item['product_id']
                        )
                        ->lockForUpdate()
                        ->first();

                    if (
                        !$warehouseProduct ||
                        $warehouseProduct->stock <
                        $item['quantity']
                    ) {

                        throw ValidationException::withMessages([
                            'stock' => [
                                'Insufficient stock'
                            ]
                        ]);
                    }

                    $price =
                        $warehouseProduct
                        ->product
                        ->price;

                    $total +=
                        $price *
                        $item['quantity'];

                    $warehouseProduct
                        ->decrement(
                            'stock',
                            $item['quantity']
                        );

                    OrderItem::create([
                        'order_id' =>
                        $order->id,

                        'product_id' =>
                        $item['product_id'],

                        'quantity' =>
                        $item['quantity'],

                        'price' => $price
                    ]);
                }

                $order->update([
                    'total_amount' => $total
                ]);

                SendOrderConfirmationJob::dispatch(
                    $order
                )->afterCommit();

                return $order->load(
                    'items.product'
                );
            }
        );

        return $this->success(
            new OrderResource($order),
            'Order Created',
            201
        );
    }
}
