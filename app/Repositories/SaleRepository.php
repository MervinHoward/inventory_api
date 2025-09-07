<?php

namespace App\Repositories;

use App\Models\Sale;
use App\Models\SaleProduct;

class SaleRepository {
    public function getAll(array $data) {
        return Sale::select($data)->with('products', 'customer')->latest()->paginate(20);
    }

    public function getById(int $id, array $data) {
        return Sale::select($data)->with('products', 'customer')->findOrFail($id);
    }

    public function create(array $data) {
        return Sale::create($data);
    }

    public function update(int $id, array $data) {
        $sale = Sale::findOrFail($id);
        $sale->update($data);
        return $sale;
    }

    public function delete(int $id) {
        $sale = Sale::findOrFail($id);
        $sale->delete();
    }

    public function createSaleProducts(int $saleId, array $products) {
        foreach ($products as $product) {
            $subTotal = $product['quantity'] * $product['price'];

            SaleProduct::create([
                'sale_id' => $saleId,
                'product_id' => $product['id'],
                'quantity' => $product['quantity'],
                'price' => $product['price'],
                'sub_total' => $subTotal
            ]);
        }
    }

    public function getTransactionByCustomer(int $customerId) {
        return Sale::where('customer_id', $customerId)->with('products')->latest()->paginate(20);
    }
}
