<?php

namespace App\Services;

use App\Repositories\UnitRepository;

class UnitService {
    private UnitRepository $unitRepository;

    public function __construct(UnitRepository $unitRepository) {
        $this->unitRepository = $unitRepository;
    }

    public function getAll(array $fields = ['*']) {
        return $this->unitRepository->getAll($fields);
    }

    public function getById(int $id, array $fields = ['*']) {
        return $this->unitRepository->getById($id, $fields);
    }

    public function create(array $data) {
        return $this->unitRepository->create($data);
    }

    public function update(int $id, array $data) {
        return $this->unitRepository->update($id, $data);
    }

    public function delete(int $id) {
        return $this->unitRepository->delete($id);
    }
}
