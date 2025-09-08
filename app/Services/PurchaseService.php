<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use App\Repositories\PurchaseRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PurchaseService
{
    private PurchaseRepository $purchaseRepository;
    private ProductRepository $productRepository;

    public function __construct(
        PurchaseRepository $purchaseRepository,
        ProductRepository $productRepository
    ) {
        $this->purchaseRepository = $purchaseRepository;
        $this->productRepository = $productRepository;
    }

    public function getAll(array $fields = ['*'])
    {
        return $this->purchaseRepository->getAll($fields);
    }

    public function getPurchaseById(int $id, array $fields = ['*'])
    {
        return $this->purchaseRepository->getById($id, $fields);
    }

    public function createPurchase(array $data)
    {
        return DB::transaction(function () use ($data) {
            if (!Auth::id()) {
                throw ValidationException::withMessages(
                    [
                        'authorization' => ['Unauthorized: You can only perform this action if you are logged in..']
                    ]
                );
            }

            $products = [];
            $grandTotal = 0;

            foreach ($data['products'] as $productData) {
                $product = $this->productRepository->getById($productData['product_id'], ['price']);
                if (!$product) {
                    throw ValidationException::withMessages(
                        [
                            'product_id' => ['Product with ID ' . $productData['product_id'] . ' not found.']
                        ]
                    );
                }
                $price = $productData['price'];
                $quantity = $productData['quantity'];
                $total = $price * $quantity;
                $grandTotal += $total;
                $products[] = [
                    'product_id' => $productData['product_id'],
                    'quantity' => $quantity,
                    'price' => $price,
                    'total' => $total
                ];
            }
            $transaction = $this->purchaseRepository->create([
                'code' => $this->purchaseRepository->generateCode($data['supplier_id'], $data['date']),
                'date' => $data['date'],
                'supplier_id' => $data['supplier_id'],
                'products' => $products,
                'grand_total' => $grandTotal
            ]);
            $this->purchaseRepository->createPurchaseProducts($transaction->id, $products);
            return $transaction->fresh();
        });
    }

    public function update(int $id, array $data)
    {
        return $this->purchaseRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->purchaseRepository->delete($id);
    }
}
