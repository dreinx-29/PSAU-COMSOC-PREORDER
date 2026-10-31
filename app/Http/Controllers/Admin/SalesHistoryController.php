<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PreOrder;
use App\Models\SalesHistory;
use Illuminate\Support\Facades\DB;

class SalesHistoryController extends Controller
{
    private function authorizeAdmin(): void
    {
        abort_unless(auth()->user()?->is_admin, 403);
    }

    public function index()
    {
        $this->authorizeAdmin();

        $salesHistories = SalesHistory::latest('reset_at')->get();

        return view('admin.sales-history.index', compact('salesHistories'));
    }

    public function reset()
    {
        $this->authorizeAdmin();

        DB::transaction(function () {
            $lastReset = SalesHistory::max('reset_at');

            $currentSales = PreOrder::where('status', 'Completed')
                ->whereNotNull('completed_at')
                ->when($lastReset, function ($query) use ($lastReset) {
                    $query->where('completed_at', '>', $lastReset);
                })
                ->sum('total_amount');

            SalesHistory::create([
                'total_sales' => $currentSales,
                'reset_at' => now(),
            ]);
        });

        return redirect()->route('admin.dashboard')
            ->with('success', 'Sales successfully archived and reset!');
    }
}
