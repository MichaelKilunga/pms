<?php

namespace App\Http\Controllers;

use App\Services\ContractUpdaterService;

class ContractUpdateController extends Controller
{
    protected $contractUpdater;

    public function __construct(ContractUpdaterService $contractUpdater)
    {
        $this->contractUpdater = $contractUpdater;
    }

    /**
     * Trigger contract updates.
     */
    public function updateContracts()
    {
        try {
            $this->contractUpdater->updateContracts();
        } catch (\Exception $e) {
            return redirect()->back()->with('success', $e->getMessage());
        }
    }
}
