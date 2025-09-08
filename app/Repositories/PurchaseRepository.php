<?php

namespace App\Repositories;

use App\Models\Purchase;
use App\Models\PurchaseProduct;
use App\Models\Supplier;
use Illuminate\Support\Str;

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

    public function getTransactionBySupplierAndDate(int $supplierId, string $date)
    {
        return Purchase::where('supplier_id', $supplierId)
            ->where('date', $date)
            ->with('products');
    }

    private function getSupplierInitial(string $supplierName): string
    {
        // Daftar kata yang akan diabaikan (case-insensitive)
        $ignoreWords = ['pt', 'cv', 'ud', 'fa', 'toko', 'firma', 'corp', 'corporation', 'dan', 'of', 'the'];

        // Pecah string menjadi array kata-kata
        $words = collect(explode(' ', Str::lower($supplierName)));

        // Filter kata-kata yang tidak masuk dalam daftar yang diabaikan
        $filteredWords = $words->filter(function ($word) use ($ignoreWords) {
            return !in_array($word, $ignoreWords) && Str::length($word) > 0;
        });

        if ($filteredWords->count() === 1) {
            // Jika hanya satu kata, ambil dua huruf pertama
            return Str::substr($filteredWords->first(), 0, 2);
        } else {
            // Jika lebih dari satu kata, ambil huruf pertama dari masing-masing kata
            return $filteredWords->map(function ($word) {
                return Str::substr($word, 0, 1);
            })->implode('');
        }
    }

    public function generateCode(int $supplierId, string $date)
    {
        $todayPurchaseCount = $this->getTransactionBySupplierAndDate($supplierId, $date)->count();
        $supplierName = Supplier::findOrFail($supplierId)->name;
        $supplierInitial = $this->getSupplierInitial($supplierName);
        $formattedDate = date('dmy', strtotime($date));
        return $supplierInitial . '-' . $formattedDate . '-' . str_pad($todayPurchaseCount + 1, 3, '0', STR_PAD_LEFT);
    }
}
