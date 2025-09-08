<?php

namespace App\Services;

use App\Repositories\ProductRepository;

class ProductService {
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository) {
        $this->productRepository = $productRepository;
    }

    public function getAll(array $fields = ['*']) {
        return $this->productRepository->getAll($fields);
    }

    public function getById(int $id, array $fields = ['*']) {
        return $this->productRepository->getById($id, $fields);
    }

    public function create(array $data) {
        return $this->productRepository->create($data);
    }

    public function update(int $id, array $data) {
        return $this->productRepository->update($id, $data);
    }

    public function delete(int $id) {
        return $this->productRepository->delete($id);
    }
}
