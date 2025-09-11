<?php

namespace App\Repositories;

use App\Models\Sale;
use App\Models\SaleProduct;
use Illuminate\Support\Str;

class SaleRepository {
    public function getAll(array $data) {
        return Sale::select($data)->with('products', 'customer')->orderBy('date')->paginate(20);
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
        return Sale::where('customer_id', $customerId)->with('products')->orderBy('date')->paginate(20);
    }

    public function getTransactionByCustomerAndDate(int $customerId, string $date)
    {
        return Sale::where('customer_id', $customerId)
            ->where('date', $date)
            ->with('products');
    }

    private function getCustomerInitial(string $customerName): string
    {
        $words = collect(explode(' ', Str::lower($customerName)));

        if ($words->count() === 1) {
            return Str::substr($words->first(), 0, 2);
        } else {
            return $words->map(function ($word) {
                return Str::substr($word, 0, 1);
            })->implode('');
        }
    }

    public function generateCode(int $customerId, string $date)
    {
        $todaySaleCount = $this->getTransactionByCustomerAndDate($customerId, $date)->count();
        $customerName = Sale::findOrFail($customerId)->name;
        $customerInitial = $this->getCustomerInitial($customerName);
        $formattedDate = date('dmy', strtotime($date));
        return $customerInitial . '-' . $formattedDate . '-' . str_pad($todaySaleCount + 1, 3, '0', STR_PAD_LEFT);
    }
}
