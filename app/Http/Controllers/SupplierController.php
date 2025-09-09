<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplierRequest;
use App\Http\Resources\SupplierResource;
use App\Services\SupplierService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SupplierController extends Controller
{
    private SupplierService $supplierService;

    public function __construct(SupplierService $supplierService)
    {
        $this->supplierService = $supplierService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fields = ['id', 'name', 'phone'];
        $suppliers = $this->supplierService->getAll($fields);
        return response()->json(SupplierResource::collection($suppliers));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SupplierRequest $request)
    {
        $supplier = $this->supplierService->create($request->validated());
        return response()->json(new SupplierResource($supplier), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            $fields = ['id', 'name', 'phone'];
            $supplier = $this->supplierService->getById($id, $fields);
            return response()->json(new SupplierResource($supplier));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Supplier not found.'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $id, SupplierRequest $request)
    {
        try {
            $supplier = $this->supplierService->update($id, $request->validated());
            return response()->json(new SupplierResource($supplier));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Supplier not found.'
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            $this->supplierService->delete($id);
            return response()->json([
                'message' => 'supplier deleted successfully.'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Supplier not found.'
            ], 404);
        }
    }
}
