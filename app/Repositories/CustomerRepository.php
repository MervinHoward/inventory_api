<?php

namespace App\Repositories;

use App\Models\Customer;

class CustomerRepository {
    public function getAll(array $fields) {
        return Customer::select($fields)->orderBy('name')->paginate(20);
    }

    public function getById(int $id, array $fields) {
        return Customer::select($fields)->findOrFail($id);
    }

    public function create(array $data) {
        return Customer::create($data);
    }

    public function update(int $id, array $fields) {
        $customer = Customer::findOrFail($id);
        $customer->update($fields);
        return $customer;
    }

    public function delete(int $id) {
        $customer = Customer::findOrFail($id);
        $customer->delete();
    }
}
