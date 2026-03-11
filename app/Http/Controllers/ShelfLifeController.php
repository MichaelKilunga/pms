<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\DisposedStock;
use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ShelfLifeController extends Controller
{
    protected function getPharmacy()
    {
        $pharmacyId = session('current_pharmacy_id');
        if (!$pharmacyId) {
            // Fallback to the user's first pharmacy if no session
            $pharmacy = Pharmacy::where('owner_id', Auth::id())->first();
            if ($pharmacy) {
                session(['current_pharmacy_id' => $pharmacy->id]);
                return $pharmacy;
            }
            abort(404, 'No pharmacy selected or found.');
        }
        return Pharmacy::findOrFail($pharmacyId);
    }

    public function index()
    {
        $pharmacy = $this->getPharmacy();
        $config = $pharmacy->config ?? [];

        $shortDatedDays = (int) ($config['short_dated_stock_duration'] ?? 90);

        $expiredCount = Stock::where('pharmacy_id', $pharmacy->id)
            ->where('expire_date', '<', Carbon::today())
            ->where('remain_Quantity', '>', 0)
            ->count();

        $shortDatedCount = Stock::where('pharmacy_id', $pharmacy->id)
            ->where('expire_date', '>=', Carbon::today())
            ->where('expire_date', '<=', Carbon::today()->addDays($shortDatedDays))
            ->where('remain_Quantity', '>', 0)
            ->count();
            
        $pendingDisposalCount = DisposedStock::forPharmacy($pharmacy->id)
            ->where('status', 'pending')
            ->count();

        return view('shelf-life.index', compact('expiredCount', 'shortDatedCount', 'pendingDisposalCount'));
    }

    public function expired()
    {
        $pharmacy = $this->getPharmacy();
        $config = $pharmacy->config ?? [];
        $expiredStocks = Stock::with('item')
            ->where('pharmacy_id', $pharmacy->id)
            ->where('expire_date', '<', Carbon::today())
            ->where('remain_Quantity', '>', 0)
            ->get();

        return view('shelf-life.expired', compact('expiredStocks'));
    }

    public function shortDated()
    {
        $pharmacy = $this->getPharmacy();
        $config = $pharmacy->config ?? [];

        $shortDatedDays = (int) ($config['short_dated_stock_duration'] ?? 90);

        $shortDatedStocks = Stock::with('item')
            ->where('pharmacy_id', $pharmacy->id)
            ->where('expire_date', '>=', Carbon::today())
            ->where('expire_date', '<=', Carbon::today()->addDays($shortDatedDays))
            ->where('remain_Quantity', '>', 0)
            ->get();

        return view('shelf-life.short-dated', compact('shortDatedStocks', 'shortDatedDays'));
    }

    public function disposed()
    {
        $pharmacy = $this->getPharmacy();
        $config = $pharmacy->config ?? [];
        $disposedStocks = DisposedStock::with(['stock.item', 'removedBy', 'approvedBy'])
            ->forPharmacy($pharmacy->id)
            ->latest()
            ->get();

        return view('shelf-life.disposed', compact('disposedStocks'));
    }

    public function dispose(Request $request)
    {
        $request->validate([
            'stock_id' => 'required|exists:stocks,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $stock = Stock::findOrFail($request->stock_id);
        
        if ($request->quantity > $stock->remain_Quantity) {
            return redirect()->back()->with('error', 'Disposal quantity exceeds remaining stock quantity.');
        }

        DisposedStock::create([
            'stock_id' => $stock->id,
            'pharmacy_id' => session('current_pharmacy_id'),
            'removed_date' => Carbon::now(),
            'expired_quantity' => $request->quantity,
            'removed_by' => Auth::id(),
            'status' => 'pending'
        ]);

        return redirect()->back()->with('success', 'Disposal request submitted for approval.');
    }

    public function approveDisposal(Request $request, $id)
    {
        $disposedStock = DisposedStock::findOrFail($id);
        
        if (!Auth::user()->hasRole('Owner')) {
            abort(403, 'Unauthorized action.');
        }

        if ($request->action === 'approve') {
            DB::transaction(function () use ($disposedStock) {
                $stock = $disposedStock->stock;
                
                // Reduce stock quantity
                $stock->remain_Quantity -= $disposedStock->expired_quantity;
                $stock->save();

                $disposedStock->update([
                    'status' => 'approved',
                    'approved_by' => Auth::id(),
                    'approved_at' => Carbon::now()
                ]);
            });
            return redirect()->back()->with('success', 'Disposal approved and stock updated.');
        } else {
            $disposedStock->update([
                'status' => 'rejected',
                'rejection_reason' => $request->reason
            ]);
            return redirect()->back()->with('info', 'Disposal request rejected.');
        }
    }
}
