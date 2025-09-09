<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleRequest;
use App\Http\Resources\SaleResource;
use App\Models\Sale;
use App\Services\SaleService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    private SaleService $saleService;

    public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fields = ['id', 'customer_id', 'code', 'date', 'grand_total', 'sale_file', 'payment_date'];
        $sales = $this->saleService->getAll($fields);
        return response()->json(SaleResource::collection($sales));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SaleRequest $request)
    {
        $sale = $this->saleService->createSale($request->validated());
        return response()->json(new SaleResource($sale), 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $id, SaleRequest $request)
    {
        try {
            $sale = $this->saleService->update($id, $request->validated());
            return response()->json(new SaleResource($sale));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Sale not found.'
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            $this->saleService->delete($id);
            return response()->json([
                'message' => 'Sale deleted successfully.'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Sale not found.'
            ]);
        }
    }
}
