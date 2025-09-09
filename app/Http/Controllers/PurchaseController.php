<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Http\Resources\PurchaseResource;
use App\Models\Purchase;
use App\Services\PurchaseService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    private PurchaseService $purchaseService;

    public function __construct(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fields = ['id', 'supplier_id', 'code', 'date', 'grand_total', 'purchase_file', 'payment_date'];
        $purchases = $this->purchaseService->getAll($fields);
        return response()->json(PurchaseResource::collection($purchases));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PurchaseRequest $request)
    {
        $product = $this->purchaseService->createPurchase($request->validated);
        return response()->json(new PurchaseResource($product), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            $fields = ['id', 'supplier_id', 'code', 'date', 'grand_total', 'purchase_file', 'payment_date'];
            $purchase = $this->purchaseService->getPurchaseById($id);
            return response()->json(new PurchaseResource($purchase));
        } catch (ModelNotFoundException $e) {
            return response()->json(
                ['messages' => 'Purchase not found.'],
                404
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $this->purchaseService->delete($id);
        return response()->json(
            ['message' => 'Purchase deleted successfully.']
        );
    }
}
