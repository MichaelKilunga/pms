<?php

namespace App\Imports;

use App\Models\Medicine;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class MedicinesImport implements ToModel, WithHeadingRow
{

    public function model(array $row) //If none of rows will be imported as all has already been imported, throw an exception
    {
        try {
            // skip duplicate rows and rows with data that has already been imported before and inform the controller
            if (Medicine::where('brand_name', $row['brand_name'])->exists()) {
                return null;
            }

            return new Medicine([
                'brand_name' => $row['brand_name'],
                'generic_name' => $row['generic_name'] ?? null,
                'description' => $row['description'] ?? null,
                'status' => $row['status'] ?? 'approved',
                'category' => $row['category'] ?? null,
                'class' => $row['class'] ?? null,
                'dosage_form' => $row['dosage_form'] ?? null,
                'strength' => $row['strength'] ?? null,
                'manufacturer' => $row['manufacturer'] ?? null,
                'manufacturing_country' => $row['manufacturing_country'] ?? null,
            ]);
        } catch (\Exception $e) {
            Log::error("Error importing row: " . $e->getMessage(), $row);
            throw new \Exception("Error importing row: " . $e->getMessage());
        }
    }
}
