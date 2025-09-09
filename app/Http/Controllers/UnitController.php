<?php

namespace App\Http\Controllers;

use App\Http\Requests\UnitRequest;
use App\Http\Resources\UnitResource;
use App\Services\UnitService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UnitController extends Controller
{
    private UnitService $unitService;

    public function __construct(UnitService $unitService)
    {
        $this->unitService = $unitService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fields = ['id', 'name'];
        $units = $this->unitService->getAll($fields);
        return response()->json(UnitResource::collection($units));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UnitRequest $request)
    {
        $unit = $this->unitService->create($request->validated());
        return response()->json(new UnitResource($unit), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            $unit = $this->unitService->getById($id);
            return response()->json(new UnitResource($unit));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Unit not found.'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $id, UnitRequest $request)
    {
        try {
            $unit = $this->unitService->update($id, $request->validated());
            return response()->json(new UnitResource($unit));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Unit not found.'
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            $this->unitService->delete($id);
            return response()->json([
                'message' => 'Unit deleted successfully.'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Unit not found.'
            ]);
        }
    }
}
