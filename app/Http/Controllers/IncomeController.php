<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\WalletType;
use App\Models\CustomField;
use App\Models\AdvanceInfo;
use App\Models\CustomFieldValue;
use App\Models\UserWallet;
use App\Models\User;
use Illuminate\Support\Str;
use App\Models\MailTemplate;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserRegisteredMail;
use App\Models\Transaction;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class IncomeController extends Controller
{


    public function incomePage($slug)
    {
        $incomeType = WalletType::where('wallet_type', 'income')
            ->where('slug', $slug)
            ->firstOrFail();
        // If slug is level, preload distinct tx_u_code users for filter
        $txUsers = [];
        if ($slug === 'level' ||  $slug === 'direct') {
            $txUsers = DB::table('transaction')
                ->join('users', 'users.id', '=', 'transaction.tx_u_code')
                ->where('transaction.u_code', auth()->id())
                ->where('transaction.tx_type', 'income')
                ->where('transaction.source', $slug)
                ->select('transaction.tx_u_code', 'users.username')
                ->distinct()
                ->get();
                
        }
        
        $totalIncome = DB::table('transaction')
    ->where('u_code', auth()->id())
    ->where('tx_type', 'income')
    ->where('status', 1)
    ->sum('amount');

$totalHold = DB::table('transaction')
    ->where('u_code', auth()->id())
    ->where('tx_type', 'income')
    ->where('status', 0)
    ->sum('amount');

$totalFlush = DB::table('transaction')
    ->where('u_code', auth()->id())
    ->where(function($q){
        $q->where('tx_type','flush_out')
          ->orWhere('status',4);
    })
    ->sum('amount');
        return view('user.income.details', compact('incomeType', 'txUsers',   'totalIncome',
    'totalHold',
    'totalFlush'));
    }
    public function incomeHistory(Request $request, $slug)
    {
        $userId = auth()->id();
  $query = DB::table('transaction')
    ->leftJoin('users', 'users.id', '=', 'transaction.tx_u_code')
    ->where('transaction.u_code', $userId)
    ->where(function ($q) use ($slug) {
        $q->where('transaction.tx_type', 'income')
          ->where('transaction.source', $slug)
          ->orWhere('transaction.tx_type', 'flush_out');
    })
    ->select(
        'transaction.*',
        'users.username as from_username'
    )
    ->orderBy('transaction.date', 'desc');
        // === Filters ===
        if ($slug === 'level' ) {
            // Filter by Level
            if ($request->filled('level') && is_numeric($request->level)) {
                $query->where('transaction.remark', 'like', "%Level {$request->level}%");
            }
            // Filter by From Username or User Code
            if ($request->filled('tx_user')) {
                $term = trim($request->tx_user);
                $query->where(function ($q) use ($term) {
                    // Search by username directly
                    $q->where('users.username', 'like', "%{$term}%");
                    // OR: convert username/usercode → user.id and match tx_u_code
                    $matchedIds = DB::table('users')
                        ->where('username', 'like', "%{$term}%")
                        ->orWhere('id', 'like', "%{$term}%")
                        ->pluck('id')
                        ->toArray();
                    if (!empty($matchedIds)) {
                        $q->orWhereIn('transaction.tx_u_code', $matchedIds);
                    }
                    
                });
            }
        }
         elseif ($slug === 'direct') {
            if ($request->filled('direct_user')) {
                $term = trim($request->direct_user);
                $query->where(function ($q) use ($term) {
                    $q->where('users.username', 'like', "%{$term}%");
                    $matchedIds = DB::table('users')
                        ->where('username', 'like', "%{$term}%")
                        ->orWhere('id', 'like', "%{$term}%")
                        ->pluck('id')
                        ->toArray();
                    if (!empty($matchedIds)) {
                        $q->orWhereIn('transaction.tx_u_code', $matchedIds);
                    }
                });
            }
        }

        if ($request->filled('status')) {
    $query->where('transaction.status', $request->status);
}



if ($request->filled('start_date')) {
    $query->whereDate('transaction.date', '>=', $request->start_date);
}

if ($request->filled('end_date')) {
    $query->whereDate('transaction.date', '<=', $request->end_date);
}
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('level', fn($row) => ($slug === 'level' && preg_match('/Level\s+(\d+)/i', $row->remark, $m)) ? $m[1] : '-')
            ->addColumn('tx_user', fn($row) => $row->from_username ?? $row->tx_u_code ?? '-')
            ->editColumn('amount', fn($row) => number_format($row->amount, 2))
  ->addColumn('status', function ($row) {

    if ($row->tx_type == 'flush_out') {
        return '<span class="badge bg-danger">Flush Out</span>';
    }

    if ($row->status == 0) {
        return '<span class="badge bg-warning">Hold</span>';
    }

    if ($row->status == 1) {
        return '<span class="badge bg-success">Released</span>';
    }

    if ($row->status == 4) {
        return '<span class="badge bg-danger">Flush</span>';
    }

    return '-';
})
->setRowClass(function ($row) {
    if ($row->tx_type == 'flush_out' || $row->status == 4) {
        return 'table-danger';
    }
    return '';
})
            ->editColumn('date', fn($row) => \Carbon\Carbon::parse($row->date)->format('d-m-Y h:i A'))
            ->editColumn('remark', fn($row) => $row->remark ?? '-')
            ->rawColumns(['status'])
            ->make(true);
    }


}
