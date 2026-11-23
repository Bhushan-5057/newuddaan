<?php   

namespace App\Http\Controllers;



use Illuminate\Http\Request;

use App\Models\WalletType;

use App\Models\CustomField;

use App\Models\AdvanceInfo;

use App\Models\CustomFieldValue;

use App\Models\UserWallet;

use App\Models\User;

use App\Models\Plan;

use App\Models\Order;

use Illuminate\Support\Str;

use App\Models\MailTemplate;

use Illuminate\Support\Facades\Mail;

use App\Mail\UserRegisteredMail;

use App\Models\Transaction;

use Carbon\Carbon;

use Illuminate\Support\Facades\DB;

use App\Models\PinDetail;

use Illuminate\Support\Facades\Auth;

use Yajra\DataTables\Facades\DataTables;

use Barryvdh\DomPDF\Facade\Pdf;



class OrderController extends Controller

{

    public function orderHistory(Request $request)

{





  $ordersQuery = Order::whereIn('tx_type', ['topup', 'retopup']);

    // Get total amount from matching orders

    $totalamount = $ordersQuery->sum('order_amount');



    // Then get the orders for the view

    $orders = $ordersQuery->orderBy('added_on', 'desc')->get();



    return view('superadmin.orders.topup_history', compact('orders', 'totalamount'));

}


public function invoice($id)
{
    // Same DomPDF template as admin invoice download
    $order = Order::with(['user', 'items.product'])
        ->where('tx_id', $id)
        ->where('u_code', Auth::id())
        ->firstOrFail();

    if (empty($order->invoice_no)) {
        $nextNo = (int) (Order::max('invoice_no') ?? 0) + 1;
        $order->invoice_no = $nextNo > 0 ? $nextNo : (int) $order->id;
        $order->save();
    }

    $html = view('admin.orders.invoice_pdf', compact('order'))->render();

    $pdf = Pdf::loadHTML($html);
    $pdf->setPaper('A4', 'portrait');

    $fileName = $order->invoiceDownloadFilename();

    return $pdf->download($fileName);
}









public function orderHistoryData(Request $request)

{

    $orders = Order::with('user')

        ->whereIn('tx_type', ['topup', 'retopup'])

        ->orderBy('added_on', 'desc');



    return DataTables::of($orders)

        ->addIndexColumn()

        ->addColumn('user_info', function ($order) {

            return $order->user ? $order->user->username : $order->u_code;

        })

        ->addColumn('status_label', function ($order) {

            return match ($order->status) {

                0 => '<span class="badge bg-warning">Pending</span>',

                1 => '<span class="badge bg-success">Approved</span>',

                2 => '<span class="badge bg-danger">Rejected</span>',

                default => '<span class="badge bg-secondary">Unknown</span>',

            };

        })
->editColumn('order_amount', function ($row) {

                return company_info('currency', '$') . number_format($row->order_amount, 2);

            })
        ->addColumn('action', function ($order) {

            return '<a href="' . route('superadmin.orders.view', $order->id) . '" class="btn btn-sm btn-info">View</a>';

        })

        ->rawColumns(['status_label', 'action'])

        ->make(true);

}

public function viewOrder($id)

{

    $order = Order::with('user')->findOrFail($id);

    return view('superadmin.orders.view', compact('order'));

}

}