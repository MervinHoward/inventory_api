<?php

namespace App\Services;

use App\Repositories\CustomerRepository;

class CustomerService {
    private CustomerRepository $customerRepository;

    public function __construct(CustomerRepository $customerRepository) {
        $this->customerRepository = $customerRepository;
    }

    public function getAll(array $fields = ['*']) {
        return $this->customerRepository->getAll($fields);
    }

    public function getById(int $id, array $fields = ['*']) {
        return $this->customerRepository->getById($id, $fields);
    }

    public function create(array $data) {
        return $this->customerRepository->create($data);
    }

    public function update(int $id, array $data) {
        return $this->customerRepository->update($id, $data);
    }

    public function delete(int $id) {
        return $this->customerRepository->delete($id);
    }
}
