<?php

namespace App\Repositories;

use App\Models\Purchase;
use App\Models\PurchaseProduct;

class PurchaseRepository
{
    public function getAll(array $fields)
    {
        return Purchase::select($fields)
            ->with('products', 'supplier')
            ->latest()
            ->paginate(20);
    }

    public function getById(int $id, array $fields)
    {
        return Purchase::select($fields)
        ->with('products', 'supplier')
        ->findOrFail($id);
    }

    public function create(array $data)
    {
        return Purchase::create($data);
    }

    public function update(int $id, array $data)
    {
        $purchase = Purchase::findOrFail($id);
        $purchase->update($data);
        return $purchase;
    }

    public function delete(int $id)
    {
        $purchase = Purchase::findOrFail($id);
        $purchase->delete();
    }

    public function createPurchaseProducts(int $purchaseId, array $products)
    {
        foreach ($products as $product) {
            $subTotal = $product['quantity'] * $product['price'];

            PurchaseProduct::create([
                'purchase_id' => $purchaseId,
                'product_id' => $product['id'],
                'quantity' => $product['quantity'],
                'price' => $product['price'],
                'sub_total' => $subTotal
            ]);
        }
    }

    public function getTransactionBySupplier(int $supplierId)
    {
        return Purchase::where('supplier_id', $supplierId)
            ->with('products')
            ->latest()
            ->paginate(20);
    }
}
