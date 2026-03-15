<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Items;
use App\Models\Sales;
use App\Models\Stock;
use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncController extends Controller
{
    /**
     * Pull updates from the server since a given timestamp.
     */
    public function pull(Request $request)
    {
        $since = $request->get('since', 0);
        $sinceDate = date('Y-m-d H:i:s', $since / 1000);

        $pharmacyId = Auth::user()->id; // Assuming staff/user association

        $updates = [
            'items' => Items::where('updated_at', '>', $sinceDate)->get()->map(function($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'version' => $item->version,
                    'last_updated' => $item->updated_at->timestamp * 1000,
                ];
            }),
            'stocks' => Stock::where('pharmacy_id', session('current_pharmacy_id'))
                ->where('updated_at', '>', $sinceDate)->get()->map(function($stock) {
                    return [
                        'id' => $stock->id,
                        'item_id' => $stock->item_id,
                        'pharmacy_id' => $stock->pharmacy_id,
                        'selling_price' => $stock->selling_price,
                        'remain_Quantity' => $stock->remain_Quantity,
                        'expire_date' => $stock->expire_date,
                        'version' => $stock->version,
                        'last_updated' => $stock->updated_at->timestamp * 1000,
                    ];
                }),
        ];

        return response()->json([
            'success' => true,
            'updates' => $updates,
            'server_time' => now()->timestamp * 1000
        ]);
    }

    /**
     * Push operations from the client to the server.
     */
    public function push(Request $request)
    {
        $payload = $request->all();
        $action = $payload['action'];
        $table = $payload['table'];
        $data = $payload['data'];

        DB::beginTransaction();
        try {
            if ($table === 'sales' && $action === 'create') {
                return $this->handleSaleCreate($data);
            }
            
            // Handle other tables/actions if needed
            
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Sync failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    private function handleSaleCreate($data)
    {
        // Find stock and update quantity
        $stock = Stock::find($data['stock_id']);
        if (!$stock || $stock->remain_Quantity < $data['quantity']) {
            throw new \Exception('Insufficient stock or stock record not found');
        }

        // Create sale record
        $sale = Sales::create([
            'pharmacy_id' => $data['pharmacy_id'],
            'staff_id' => $data['staff_id'],
            'item_id' => $data['item_id'],
            'stock_id' => $data['stock_id'],
            'quantity' => $data['quantity'],
            'total_price' => $data['total_price'],
            'amount' => $data['amount'],
            'date' => $data['date'],
            'version' => 1,
        ]);

        // Decrement stock
        $stock->decrement('remain_Quantity', $data['quantity']);
        $stock->increment('version'); // Increment version on change

        DB::commit();

        return response()->json([
            'success' => true,
            'server_id' => $sale->id,
            'local_id' => $data['local_id'] ?? null
        ]);
    }
}
