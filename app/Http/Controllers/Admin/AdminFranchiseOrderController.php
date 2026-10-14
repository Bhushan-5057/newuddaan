<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FranchiseOrder;
use App\Models\FranchiseUser;
use App\Models\TransactionFranchise;
use App\Models\FranchiseOrderItem;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class AdminFranchiseOrderController extends Controller
{
    public function franchiseOrders()
    {
        return view('admin.orders.franchise_orders', [
            'totalAmount' => FranchiseOrder::sum('order_amount'),
            'totalCount'  => FranchiseOrder::count(),
        ]);
    }

    public function franchiseOrdersData(Request $request)
    {
        $orders = FranchiseOrder::with('franchise');

        // Username filter
        if ($request->filled('username')) {
            $orders->whereHas('franchise', function ($q) use ($request) {
                $q->where('username', 'like', '%' . $request->username . '%');
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $orders->where('status', $request->status);
        }

        return DataTables::of($orders)
            ->addIndexColumn()

            ->addColumn('franchise_info', function ($o) {
                return $o->franchise
                    ? $o->franchise->username . ' (' . $o->franchise->name . ')'
                    : '-';
            })

            ->addColumn('status_label', function ($o) {
                return match ($o->status) {
                    0 => '<span class="badge bg-warning">Pending</span>',
                    1 => '<span class="badge bg-success">Approved</span>',
                    2 => '<span class="badge bg-danger">Rejected</span>',
                    3 => '<span class="badge bg-info">Partial</span>',
                };
            })

            ->addColumn('action', function ($o) {
                return '<a href="' . route('admin.franchise.orders.view', $o->id) . '"
                    class="btn btn-sm btn-primary">View</a>';
            })

            ->editColumn(
                'created_at',
                fn($o) =>
                $o->created_at->format('d M Y h:i A')
            )

            ->rawColumns(['status_label', 'action'])
            ->make(true);
    }
    public function viewFranchiseOrder($id)
    {
        $order = FranchiseOrder::with([
            'franchise',
            'items.product'
        ])->findOrFail($id);

        return view('admin.orders.franchise_view', compact('order'));
    }


    public function approveFranchiseOrder($itemId)
    {
        try {

            DB::transaction(function () use ($itemId) {

                $item = FranchiseOrderItem::with(['order', 'product'])
                    ->lockForUpdate()
                    ->findOrFail($itemId);

                if ($item->status != 0) {
                    throw new \Exception('Item already processed');
                }

                $order = $item->order;
                $franchise = $order->franchise;
                $currancy = company_info('currency', '$');

                // ✅ Wallet check
                if ((float)$franchise->balance < (float)$item->sub_total) {
                    throw new \Exception(
                        'Insufficient wallet balance. Required' . $currancy .
                            number_format($item->sub_total, 2) .
                            ', Available ' .  $currancy .
                            number_format($franchise->balance, 2)
                    );
                }

                // Deduct wallet
                $open  = $franchise->balance;
                $close = $open - $item->sub_total;

                $franchise->update(['balance' => $close]);

                // Stock check
                if ($item->product->qty < $item->quantity) {
                    throw new \Exception('Insufficient stock');
                }

                $item->product->decrement('qty', $item->quantity);

                // Transaction entry
                TransactionFranchise::create([
                    'u_code'        => $franchise->username,
                    'tx_type'       => 'franchise_order_item',
                    'debit_credit'  => 'debit',
                    'wallet_type'   => 'main_wallet',
                    'amount'        => $item->sub_total,
                    'open_wallet'   => $open,
                    'closing_wallet' => $close,
                    'remark'        => 'Order Item Approved',
                    'status'        => 1,
                ]);

                // Approve item
                $item->update([
                    'status' => 1,
                    'approved_at' => now()
                ]);

                $this->updateOrderStatus($order->id);
            });

            return back()->with('success', 'Item approved successfully');
        } catch (\Exception $e) {

            return back()->withErrors([
                'wallet' => $e->getMessage()
            ]);
        }
    }
    public function rejectFranchiseOrder($itemId)
    {
        $item = FranchiseOrderItem::findOrFail($itemId);

        if ($item->status != 0) {
            return back()->withErrors(['error' => 'Item already processed']);
        }

        $item->update([
            'status' => 2,
            'rejected_at' => now()
        ]);

        $this->updateOrderStatus($item->order_id);

        return back()->with('success', 'Item rejected');
    }

    private function updateOrderStatus($orderId)
    {
        $items = FranchiseOrderItem::where('order_id', $orderId)->get();

        if ($items->every(fn($i) => $i->status == 1)) {
            $status = 1; // approved
        } elseif ($items->every(fn($i) => $i->status == 2)) {
            $status = 2; // rejected
        } elseif ($items->contains(fn($i) => $i->status == 1)) {
            $status = 3; // partial
        } else {
            $status = 0; // pending
        }

        FranchiseOrder::where('id', $orderId)->update(['status' => $status]);
    }
}
