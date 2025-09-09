<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Services\CustomerService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CustomerController extends Controller
{
    private CustomerService $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fields = ['id', 'name', 'phone'];
        $customers = $this->customerService->getAll($fields);
        return response()->json(CustomerResource::collection($customers));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerRequest $request)
    {
        $customer = $this->customerService->create($request->validated());
        return response()->json(new CustomerResource($customer), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            $fields = ['id', 'name', 'phone'];
            $customer = $this->customerService->getById($id, $fields);
            return response()->json(new CustomerResource($customer));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Customer not found.'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $id, CustomerRequest $request)
    {
        try {
            $customer = $this->customerService->update($id, $request->validated());
            return response()->json(new CustomerResource($customer));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Customer not found.'
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            $this->customerService->delete($id);
            return response()->json([
                'message' => 'Customer deleted successfully.'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Customer not found.'
            ], 404);
        }
    }
}
