<?php

namespace App\Services;

use App\Repositories\SaleRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SaleService {
    private SaleRepository $saleRepository;

    public function __construct(SaleRepository $saleRepository) {
        $this->saleRepository = $saleRepository;
    }

    public function getAll(array $fields = ['*']) {
        return $this->saleRepository->getAll($fields);
    }

    public function getById(int $id, array $fields = ['*']) {
        return $this->saleRepository->getById($id, $fields);
    }

    public function createSale(array $data) {
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
                $product = $this->saleRepository->getById($productData['product_id'], ['price']);
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
            $transaction = $this->saleRepository->create([
                'code' => $this->saleRepository->generateCode($data['supplier_id'], $data['date']),
                'date' => $data['date'],
                'supplier_id' => $data['supplier_id'],
                'products' => $products,
                'grand_total' => $grandTotal
            ]);
            $this->saleRepository->createSaleProducts($transaction->id, $products);
            return $transaction->fresh();
        });
    }

    public function update(int $id, array $data) {
        return $this->saleRepository->update($id, $data);
    }

    public function delete(int $id) {
        return $this->saleRepository->delete($id);
    }
}
