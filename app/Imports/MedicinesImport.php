<?php

namespace App\Imports;

use App\Models\Medicine;
use Maatwebsite\Excel\Concerns\ToModel;

class MedicinesImport implements ToModel
{
    public function model(array $row)
    {
        return new Medicine([
            'name' => $row['name'],
            'status' => $row['status'] ?? 'approved',
        ]);
    }
}
