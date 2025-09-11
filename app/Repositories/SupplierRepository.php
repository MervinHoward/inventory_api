<?php

namespace App\Repositories;

use App\Models\Supplier;

class SupplierRepository {
    public function getAll(array $fields) {
        return Supplier::select($fields)->orderBy('name')->paginate(20);
    }

    public function getById(int $id, array $fields) {
        return Supplier::select($fields)->findOrFail($id);
    }

    public function create(array $data) {
        return Supplier::create($data);
    }

    public function update(int $id, array $data) {
        $supplier = Supplier::findOrFail($id);
        $supplier->update($data);
        return $supplier;
    }

    public function delete(int $id) {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();
    }
}
