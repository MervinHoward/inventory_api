<?php

namespace App\Repositories;

use App\Models\Unit;

class UnitRepository
{
    public function getAll(array $fields)
    {
        return Unit::select($fields)->orderBy('name')->paginate(20);
    }

    public function getById(int $id, array $fields)
    {
        return Unit::select($fields)->findOrFail($id);
    }

    public function create(array $data)
    {
        return Unit::create($data);
    }

    public function update(int $id, array $data)
    {
        $unit = Unit::findOrFail($id);
        $unit->update($data);
        return $unit;
    }

    public function delete(int $id)
    {
        $unit = Unit::findOrFail($id);
        $unit->delete();
    }
}
