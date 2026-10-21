<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\DreamCarBonusController;
use App\Http\Controllers\LeadershipCircleBonusController;
use App\Http\Controllers\BonanzaController;
use App\Http\Controllers\UserBonanzaController;
use App\Http\Controllers\AdminAccountController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\CustomFieldController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CompanyInfoController;
use App\Http\Controllers\AdvanceInfoController;
use App\Http\Controllers\MailTemplateController;
use App\Http\Controllers\FundController;
use App\Http\Controllers\SubAdminController;
use App\Http\Controllers\UserFundController;
use App\Http\Controllers\AdminFundController;
use App\Http\Controllers\PinDetailsCustomFieldController;
use App\Http\Controllers\PlanCustomFieldController;
use App\Http\Controllers\PinDetailsController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\AdminPinDetailsCustomFieldController;
use App\Http\Controllers\AdminPlanCustomFieldController;
use App\Http\Controllers\AdminPinDetailsController;
use App\Http\Controllers\AdminPlanController;
use App\Http\Controllers\ManualMatchingController;
use App\Http\Controllers\RoiController;
use App\Http\Controllers\InvestController;
use App\Http\Controllers\RankController;
use App\Http\Controllers\UserPayoutController;
use App\Http\Controllers\WithdrwalsController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserKycController;
use App\Http\Controllers\AdminKycController;
use App\Http\Controllers\AdminCustomFieldController;
use App\Http\Controllers\AdminMailTemplateController;
use App\Http\Controllers\AdminWithdrwalsController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\AdminAdvanceInfoController;
use App\Http\Controllers\AdminCompanyInfoController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\CompanyPaymentMethodController;
use App\Http\Controllers\AdminPaymentMethodController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\SuperAdminSupportController;
use App\Http\Controllers\SuperAdminIncomeController;
use App\Http\Controllers\AdminIncomeController;
use App\Http\Controllers\AdminSupportController;
use App\Http\Controllers\NoticeBoardController;
use App\Http\Controllers\AdminNoticeBoardController;
use App\Http\Controllers\IncomeReportController;
use App\Http\Controllers\TreeViewController;
use App\Http\Controllers\GenealogyController;
use App\Http\Controllers\NewsEventsController;
use App\Http\Controllers\BoosterPackageController;
use App\Http\Controllers\AdminIncomeReportController;
use App\Http\Controllers\SuperAdminIncomeReportController;
use App\Http\Controllers\LoginHistoryController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use App\Http\Controllers\Auth\UserLoginController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\SuperAdminLoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\FranchiseController;
use App\Http\Controllers\Admin\AdminFranchiseOrderController;
use App\Http\Controllers\Admin\RankRewardController;
use App\Http\Controllers\Admin\AdminFranchiseFundRequestController;
use App\Http\Controllers\Franchise\Auth\LoginController as FranchiseLoginController;

use App\Http\Controllers\Main\HomeController;
use App\Http\Controllers\Main\PaymentController;

use App\Http\Controllers\Main\ContactController;

use App\Models\Cart;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;







// use App\Http\Controllers\Franchise\DashboardController;
use App\Http\Controllers\Franchise\FranchiseProfileController;
use App\Http\Controllers\Franchise\OrderController as FranchiseOrderController;
use App\Helpers\UserHelper;
use App\Models\AdvanceInfo;




Route::get('/test-mail', function () {

    try {

        Mail::raw('Testing Email', function ($message) {
            $message->to('bd.codeelevator123@gmail.com')
                    ->subject('Testing Mail');
        });

        return "Mail Sent Successfully";

    } catch (\Exception $e) {
        return $e->getMessage();
    }

});


Route::view('/', 'main.index.index')->middleware('checkSiteStatus');

Route::view('/faq', 'main.pages.faq')->name('faq');

Route::view('/privacy-policy', 'main.pages.privacy-policy')->name('privacy.policy');
Route::view('/terms-conditions', 'main.pages.terms-conditions')->name('terms.conditions');
Route::view('/refund-policy', 'main.pages.refund-policy')->name('refund.policy');
Route::view('/shipping-policy', 'main.pages.shipping-policy')->name('shipping.policy');


// 1. Forgot Password Routes (Link mokalva mate)
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
    ->name('password.request');

Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->name('password.email');

// 2. Reset Password Routes (Password badlava mate)
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
    ->name('password.reset');

Route::post('/reset-password', [ResetPasswordController::class, 'reset'])
    ->name('password.update');



Route::get('/', [HomeController::class, 'index'])->name('home')->middleware('checkSiteStatus');
Route::get('/product/{slug}', [HomeController::class, 'productDetails'])->name('product.details');
// Category and Sub-category products
// Route::get('/category/{category_slug}/{subcategory_slug?}', [HomeController::class, 'categoryProducts'])->name('category.view');
// Category માટે
Route::get('/category/{slug}', [HomeController::class, 'categoryProducts'])->name('category.view');

// Sub-category માટે
Route::get('/subcategory/{slug}', [HomeController::class, 'categoryProducts'])->name('subcategory.view');

// All Products / Shop Page Route
Route::get('/shop', [HomeController::class, 'shop'])->name('product.shop');
Route::get('/get-counts', [HomeController::class, 'getCounts'])->name('cart.wishlist.count');

// Search Suggestions (AJAX)
Route::get('/search-suggestions', [HomeController::class, 'searchSuggestions'])->name('product.search.suggestions');


// Global Search Route
Route::get('/search', [HomeController::class, 'search'])->name('product.search');
Route::get('/search-suggestions', [HomeController::class, 'searchSuggestions'])->name('product.search.suggestions');


Route::get('/about', [HomeController::class, 'about'])->name('about.index');
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact/store', [ContactController::class, 'store'])->name('contact.store');

  Route::post('/wishlist/add', [HomeController::class, 'wishlistStore'])->name('wishlist.store');
      Route::post('/product/cart', [HomeController::class, 'cartstore'])->name('cart.store');

Route::middleware(['auth:web', 'role:user,web', 'checkSiteStatus'])->group(function () {

    // Cart Routes

    Route::post('/cart/remove/{id}', [HomeController::class, 'cartRemove'])->name('cart.remove');
    Route::get('/checkout', [HomeController::class, 'checkout'])->name('cart.index');
    Route::post('/cart/update', [HomeController::class, 'cartupdate'])->name('cart.update');

    // Wishlist Routes (આ રૂટ્સ પર ખાસ ધ્યાન આપો)
    Route::get('/cart', [HomeController::class, 'cartIndex'])->name('cart');
    
    Route::get('/wishlist', [HomeController::class, 'wishlistIndex'])->name('wishlist.index');
  
    Route::get('/wishlist/remove/{id}', [HomeController::class, 'wishlistRemove'])->name('wishlist.remove');

    // Order Route
    Route::post('/address/store', [HomeController::class, 'delivery_contacts'])->name('address.store');
       Route::delete('/address/delete/{id}', [HomeController::class, 'delete_delivery_address'])->name('address.delete');
    Route::post('/place-order', [HomeController::class, 'placeOrder'])->name('order.place');
    // Order Success / Invoice Route
    Route::get('/order/invoice/{id}', [OrderController::class, 'invoice'])->name('order.invoice');
    
    
     // Cashfree Payment Routes
    Route::post('/payment/initiate', [PaymentController::class, 'initiatePayment'])->name('payment.initiate');
    Route::get('/payment/callback', [PaymentController::class, 'paymentCallback'])->name('payment.callback');
    Route::post('/payment/webhook', [PaymentController::class, 'webhook'])->name('payment.webhook');

    
});



Route::get('/cart/sidebar', function () {
    if (!auth()->check()) {
        return view('main.partials.cart_sidebar');
    }

    return view('main.partials.cart_sidebar');
})->name('cart.sidebar');






Route::get('/compare', fn() => 'compare')->name('compare');




$loginType = AdvanceInfo::where('label', 'Registration_Type')->value('value') ?? 'normal';


// Normal Login/Register routes
if ($loginType == 'normal') {
    Route::middleware('redirect.auth.guard:web')->get('/login', [UserLoginController::class, 'showLoginForm'])->name('login'); // ✅ REQUIRED for route('login') to work
    // Intentionally unnamed: the GET route above owns the 'login' name, and a
    // duplicate name makes route:cache refuse to build. Both share the /login
    // URI, so route('login') still produces the correct form action.
    Route::post('/login', [UserLoginController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
}

// dapp Login Register
if ($loginType == 'dapp') {
    // Dapp Login Register
    Route::middleware('redirect.auth.guard:web')->get('/login', [UserLoginController::class, 'showLoginFormdapp'])->name('login'); // ✅ REQUIRED for route('login') to work
    Route::post('login', [UserLoginController::class, 'logindapp'])->name('logindapp');
    Route::get('register', [AuthController::class, 'showRegisterFormdapp'])->name('register');
    Route::post('user/register', [AuthController::class, 'registerdapp'])->name('registerdapp');
}

// NEW: live AJAX uniqueness checks
Route::get('/check-username', [AuthController::class, 'checkUsername'])->name('auth.checkUsername');
Route::get('/check-pan', [AuthController::class, 'checkPan'])->name('auth.checkPan');
Route::get('/check-aadhaar', [AuthController::class, 'checkAadhaar'])->name('auth.checkAadhaar');
Route::get('/check-unique-name', [AuthController::class, 'checkUniqueName'])->name('auth.checkUniqueName');

// Alias URL for the same lookup. The canonical name 'auth.getSponsorName'
// belongs to /users/getSponsorName (which is what route() already resolved to,
// being registered later); this one needs its own name so route:cache can build.
Route::get('/auth/get-sponsor-name', [AuthController::class, 'getSponsorName'])->name('auth.getSponsorName.alias');
Route::get('/register-success', [AuthController::class, 'registerSuccess'])->name('register.success');






// matching Income
Route::get('/cron/run-matching-income', function () {
    Artisan::call('matching:closing');
    return response()->json([
        'status' => 'success',
        'message' => 'matching closing executed successfully.'
    ]);
});

// Rank Rise Bonus 
Route::get('/cron/run-Rank-closing', function () {
    Artisan::call('rank:closing');
    return response()->json([
        'status' => 'success',
        'message' => 'Rank closing executed successfully.'
    ]);
});
// Royalty Distribut Bonus 
Route::get('/cron/run-rankreward-income', function () {
    Artisan::call('rank:distribute-income');
    return response()->json([
        'status' => 'success',
        'message' => 'Royalty income distributed successfully.'
    ]);
});


// Monthly Hold Income Release
Route::get('/cron/run-monthly-income-release', function () {
    Artisan::call('royalty:income');
    return response()->json([
        'status' => 'success',
        'message' => 'Monthly hold income released successfully.'
    ]);
});

// Auto Withdrawal Process
Route::get('/cron/run-withdrawal-auto', function () {
    Artisan::call('withdrawal:auto');
    return response()->json([
        'status' => 'success',
        'message' => 'Auto withdrawal executed successfully.'
    ]);
});




/*
|--------------------------------------------------------------------------
| FRANCHISE ROUTES (WEB.PHP)
|--------------------------------------------------------------------------
*/


// Add this after your other routes
Route::post('/logout', [LogoutController::class, 'userLogout'])->name('user.logout');

Route::middleware(['auth:franchise', 'franchise.session'])->group(function () {

    // ✅ FRANCHISE DASHBOARD
    Route::get('franchise/dashboard', [DashboardController::class, 'index'])->name('franchise.dashboard');

    Route::get('franchise/profile', [FranchiseProfileController::class, 'index'])->name('franchise.profile');

    Route::post('franchise/profile', [FranchiseProfileController::class, 'update'])->name('franchise.profile.update');

    Route::get('franchise/change-password', function () {
        return view('franchise.profile.change-password');
    })->name('franchise.password');

    // orders
    Route::get('orders', [FranchiseOrderController::class, 'index'])->name('franchise.orders.index');
    Route::get('orders/create', [FranchiseOrderController::class, 'create'])->name('franchise.orders.create');
    Route::post('orders', [FranchiseOrderController::class, 'store'])->name('franchise.orders.store');
    Route::get('orders/{id}', [FranchiseOrderController::class, 'show'])->name('franchise.orders.show');

    Route::get('fundRequest', [FranchiseOrderController::class, 'fundRequest'])->name('franchise.fund.request');
    Route::post('fundRequestStore', [FranchiseOrderController::class, 'fundRequestStore'])->name('franchise.fund.store');

    // ✅ LOGOUT
    Route::post('/franchise/logout', [LogoutController::class, 'franchiseLogout'])->name('franchise.logout');
});


Route::middleware(['auth:franchise'])->prefix('franchise')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'franchisedashboard'])->name('franchise.dashboard');
});



// FRANCHISE
Route::prefix('franchise')->middleware(['redirect.auth.guard:franchise'])->group(function () {
    Route::get('/login', [FranchiseLoginController::class, 'showLoginForm'])->name('franchise.login');
    Route::post('/login', [FranchiseLoginController::class, 'login'])->name('franchise.login.submit');
});


// USER LOGOUT ROUTE (Separate from admin logout)



// Placeholder kept for the legacy /dashboard URL. The real route named
// 'dashboard.user' is DashboardController@userdashboard; this one must stay
// unnamed or route:cache refuses to build.
Route::middleware(['auth:web'])->get('/dashboard', fn() => 'User Dashboard');
// ADMIN
Route::prefix('admin')->middleware(['admin.session', 'redirect.auth.guard:admin'])->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('adminlogin');

});


Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('otp', [AdminLoginController::class, 'showOtpForm'])->name('otp.form');
    Route::post('otp/verify', [AdminLoginController::class, 'verifyOtp'])->name('otp.verify');
    Route::post('otp/resend', [AdminLoginController::class, 'resendOtp'])->name('resend.otp');
    
    // Admin logout route
    Route::post('logout', [LogoutController::class, 'adminLogout'])->name('logout');
});

// Placeholder kept for the legacy /admin/dashboard URL. The real route named
// 'dashboard.admin' is DashboardController@admindashboard below; this one must
// stay unnamed or route:cache refuses to build.
Route::middleware(['auth:admin'])->get('/admin/dashboard', fn() => 'Admin Dashboard');
// SUPER ADMIN
Route::prefix('superadmin')->middleware(['superadmin.session', 'redirect.auth.guard:superadmin'])->group(function () {
    Route::get('/login', [SuperAdminLoginController::class, 'showLoginForm'])->name('superadmin.login');
    Route::post('/login', [SuperAdminLoginController::class, 'login']);
});
Route::post('/superadmin/logout', [LogoutController::class, 'superAdminLogout'])->name('superadmin.logout');
// See the note on /admin/dashboard above.
Route::middleware(['auth:superadmin'])->get('/superadmin/dashboard', fn() => 'Superadmin Dashboard');
Route::get('/link-storage', function () {
    Artisan::call('storage:link');
    return 'Storage symlink created successfully.';
})->name('command.storage.link');
Route::get('/migrateseed', function () {
    // Run migration
    Artisan::call('migrate', ['--force' => true]);
    // Run seeder
    Artisan::call('db:seed', ['--force' => true]);
    return 'Migration and Seeding Done!';
});
Route::get('/runartisancommands', function () {
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('view:cache');
    return response()->json([
        'message' => 'Artisan commands executed successfully!',
        'output' => Artisan::output(),
    ]);
});
Route::get('/reload-storage-path', function () {
    $storageLink = public_path('storage');
    // Remove the existing symbolic link if it exists
    if (is_link($storageLink)) {
        unlink($storageLink); // Remove the symbolic link
    } elseif (file_exists($storageLink)) {
        // If it's not a symbolic link but a regular folder or file, delete it
        \Illuminate\Support\Facades\File::deleteDirectory($storageLink);
    }
    // Recreate the symbolic link
    try {
        Artisan::call('storage:link');
        return response()->json([
            'message' => 'Storage path reloaded successfully!',
            'output' => Artisan::output(),
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed to reload storage path.',
            'error' => $e->getMessage(),
        ], 500);
    }
});
Route::get('/clear-cache', function () {
    Artisan::call('optimize:clear');
    return 'All Laravel caches cleared!';
});


// Binary Matching Closing
Route::get('/run-matching-closing', function () {
    Artisan::call('matching:closing');
    return redirect()->route('dashboard.superadmin')
        ->with('success', 'Binary matching closing executed successfully.');
})->name('command.matching.closing');


// Monthly Rank Achievement Closing
Route::get('/run-rank-closing', function () {
    Artisan::call('rank:closing');
    return redirect()->route('dashboard.superadmin')
        ->with('success', 'Monthly rank closing executed successfully.');
})->name('command.rank.closing');


// Rank Income Distribution (2% CTO)
Route::get('/run-rank-distribute', function () {
    Artisan::call('rank:distribute-income');
    return redirect()->route('dashboard.superadmin')
        ->with('success', 'Rank income distribution executed successfully.');
})->name('command.rank.distribute.income');


// Monthly Hold Income Release
Route::get('/run-monthly-income-release', function () {
    Artisan::call('royalty:income');
    return redirect()->route('dashboard.superadmin')
        ->with('success', 'Monthly hold income release executed successfully.');
})->name('command.monthly.income.release');


// Auto Withdrawal
Route::get('/run-auto-withdrawal', function () {
    Artisan::call('withdrawal:auto');
    return redirect()->route('dashboard.superadmin')
        ->with('success', 'Auto withdrawal executed successfully.');
})->name('command.withdrawal.auto');



Route::get('roi/export', [IncomeController::class, 'exportRoiHistory'])->name('roi.export');
Route::middleware(['auth:superadmin', 'superadmin.session', 'role:super admin,superadmin'])->group(function () {
    // Route::middleware(['auth', 'role:super admin'])->group(function () {
    Route::get('/dashboard/superadmin', [DashboardController::class, 'superadmindashboard'])->name('dashboard.superadmin');
    Route::get('/dashboard/superadmin/recentUserData', [DashboardController::class, 'recentUserData'])->name('superadmin.dashboard.recentUserData');
    Route::get('/superadminusers', [UserController::class, 'superadminindex'])->name('superadminusers.index');
    Route::get('/superadminusers/users/data', [UserController::class, 'superadmingetData'])->name('superadminusers.users.data');
    Route::post('/superadminusers/create', [UserController::class, 'superadmincreate'])->name('superadminusers.create');
    Route::get('/superadminusers/{id}/edit', [UserController::class, 'superadminedit'])->name('superadminusers.edit');
    Route::put('/superadminusers/{id}', [UserController::class, 'superadminupdate'])->name('superadminusers.update');
    Route::delete('/superadminusers/{id}', [UserController::class, 'superadmindestroy']);
    Route::get('/superadmin/change-password', [AccountController::class, 'showChangePasswordForm'])->name('superadmin.change-password.form');
    Route::post('/superadmin/change-password', [AccountController::class, 'updatePassword'])->name('superadmin.change-password.update');
    Route::get('/company-settings', [RolePermissionController::class, 'index'])->name('company.settings');
    Route::get('/superadmin/profile', [UserController::class, 'superadminprofile'])->name('superadmin.profile');
    Route::get('/superadmin/profile/edit', [ProfileController::class, 'edit'])->name('superadmin.profile.edit');
    Route::post('/superadmin/profile/update', [ProfileController::class, 'update'])->name('superadmin.profile.update');
    Route::get('/superadmin/company-info', [CompanyInfoController::class, 'index'])->name('superadmin.companyinfo.index');
    Route::get('/superadmin/company-info/data', [CompanyInfoController::class, 'getData'])->name('superadmin.companyinfo.data');
    Route::post('/superadmin/company-info/store', [CompanyInfoController::class, 'store'])->name('superadmin.companyinfo.store');
    Route::post('company-info/update', [CompanyInfoController::class, 'update'])->name('superadmin.companyinfo.update');
    Route::get('superadmin/advance-info', [AdvanceInfoController::class, 'index'])->name('superadmin.advanceinfo.index');
    Route::get('superadmin/advance-info/data', [AdvanceInfoController::class, 'getData'])->name('superadmin.advanceinfo.data');
    Route::post('superadmin/advance-info/store', [AdvanceInfoController::class, 'store'])->name('superadmin.advanceinfo.store');
    Route::get('superadmin/advance-info/edit/{id}', [AdvanceInfoController::class, 'edit'])->name('superadmin.advanceinfo.edit'); // THE CRITICAL EDIT ROUTE
    Route::delete('superadmin/advance-info/delete/{id}', [AdvanceInfoController::class, 'destroy'])->name('superadmin.advanceinfo.delete');
    Route::put('superadmin/advance-info/update/{id}', [AdvanceInfoController::class, 'update'])->name('superadmin.advance-info.update');
    // fund
    Route::get('/superadmin/fund/add', [FundController::class, 'addFundindex'])->name('fund.index');
    Route::post('/add-fund', [FundController::class, 'addFund'])->name('fund.add');
    Route::get('/superadmin/transferfundtransactions/index', [FundController::class, 'transferfundtransactionsindex'])->name('superadmin.transferfundtransactions.index');
    Route::get('superadmin/transferfundtransactions/list', [FundController::class, 'TransferFundtransactionslist'])->name('superadmin.transferfundtransactions.list');
    Route::get('/superadmin/fund/retrieve', [FundController::class, 'RetrieveFundindex'])->name('retrievefund.index');
    Route::post('/superadmin/fund/retrieve', [FundController::class, 'retrieveFund'])->name('fund.retrieve');
    Route::get('/superadmin/retrievefundtransactions/index', [FundController::class, 'retrievefundtransactionsindex'])->name('superadmin.retrievefundtransactions.index');
    Route::get('superadmin/retrievefundtransactions/list', [FundController::class, 'retrievefundtransactionslist'])->name('superadmin.retrievefundtransactions.list');
    Route::get('/superadmin/transferfundtransactionsrequest/index', [FundController::class, 'fundrequestindex'])->name('superadmin.transferfundtransactionsrequest.index');
    Route::get('superadmin/transferfundtransactionsrequest/list', [FundController::class, 'fundrequestlist'])->name('superadmin.transferfundtransactionsrequest.list');
    Route::get('/superadmin/fundrequest/{id}/detail', [FundController::class, 'fundrequestDetail'])->name('superadmin.transferfundtransactionsrequest.detail');
    Route::post('superadmin/transferfundtransactionsrequest/approve', [FundController::class, 'approve'])->name('superadmin.transferfundtransactionsrequest.approve');
    Route::post('superadmin/transferfundtransactionsrequest/reject', [FundController::class, 'reject'])->name('superadmin.transferfundtransactionsrequest.reject');
    Route::get('/superadmin/approvedfundrequest/index', [FundController::class, 'approvefundrequestindex'])->name('superadmin.approvedfundrequest.index');
    Route::get('superadmin/approvedfundrequest/list', [FundController::class, 'approvefundrequestlist'])->name('superadmin.approvedfundrequest.list');
    Route::get('/superadmin/cancelledfundrequest/index', [FundController::class, 'cancelledfundrequestindex'])->name('superadmin.cancelledfundrequest.index');
    Route::get('superadmin/cancelledfundrequest/list', [FundController::class, 'cancelledfundrequestlist'])->name('superadmin.cancelledfundrequest.list');
    Route::get('/superadmin/fund-convert/history', [FundController::class, 'fundConvertHistory'])->name('superadmin.fund.convert.history');
    Route::get('/superadmin/fund-convert/history/data', [FundController::class, 'fundConvertHistoryData'])->name('superadmin.fund.convert.history.data');
    // withdrwals
    Route::get('/superadmin/withdrwalstransactionsrequest/index', [WithdrwalsController::class, 'withdrwalsrequestindex'])->name('superadmin.withdrwalstransactionsrequest.index');
    Route::get('/superadmin/withdrwalstransactionsrequest/list', [WithdrwalsController::class, 'withdrwalsrequestlist'])->name('superadmin.withdrwalstransactionsrequest.list');
    Route::get('/superadmin/withdrwalsrequest/{id}/detail', [WithdrwalsController::class, 'withdrwalsrequestDetail'])->name('superadmin.withdrwalsrequest.detail');
    Route::post('/superadmin/withdrwalstransactionsrequest/approve', [WithdrwalsController::class, 'approve'])->name('superadmin.withdrwalstransactionsrequest.approve');
    Route::post('/superadmin/withdrwalstransactionsrequest/reject', [WithdrwalsController::class, 'reject'])->name('superadmin.withdrwalstransactionsrequest.reject');
    Route::get('/superadmin/approvedwithdrwalsrequest/index', [WithdrwalsController::class, 'approvewithdrwalsrequestindex'])->name('superadmin.approvedwithdrwalsrequest.index');
    Route::get('/superadmin/approvedwithdrwalsrequest/list', [WithdrwalsController::class, 'approvewithdrwalsrequestlist'])->name('superadmin.approvedwithdrwalsrequest.list');
    Route::get('/superadmin/cancelledwithdrwalsrequest/index', [WithdrwalsController::class, 'cancelledwithdrwalsrequestindex'])->name('superadmin.cancelledwithdrwalsrequest.index');
    Route::get('/superadmin/cancelledwithdrwalsrequest/list', [WithdrwalsController::class, 'cancelledwithdrwalsrequestlist'])->name('superadmin.cancelledwithdrwalsrequest.list');
    Route::prefix('superadmin')->name('superadmin.')->group(function () {
        Route::get('mail-templates', [MailTemplateController::class, 'index'])->name('mailtemplates.index');
        Route::get('mail-templates/data', [MailTemplateController::class, 'getData'])->name('mailtemplates.data');
        Route::get('mail-templates/{mailTemplate}/preview', [MailTemplateController::class, 'preview'])->name('mailtemplates.preview');
        Route::get('mail-templates/create', [MailTemplateController::class, 'create'])->name('mailtemplates.create');
        Route::post('mail-templates', [MailTemplateController::class, 'store'])->name('mailtemplates.store');
        Route::get('mail-templates/{mailTemplate}/edit', [MailTemplateController::class, 'edit'])->name('mailtemplates.edit');
        Route::put('mail-templates/{mailTemplate}', [MailTemplateController::class, 'update'])->name('mailtemplates.update');
        Route::delete('mail-templates/{mailTemplate}', [MailTemplateController::class, 'destroy'])->name('mailtemplates.destroy');
    });
    // Route::get('/superadmin/mail-templates', [MailTemplateController::class, 'create'])->name('superadmin.mailtemplates.create');
    // Route::get('/superadmin/mail-templates/store', [MailTemplateController::class, 'store'])->name('superadmin.mailtemplates.store');
    Route::prefix('superadmin')->name('superadmin.')->group(function () {
        Route::get('/pindetails/index', [PinDetailsController::class, 'index'])->name('pindetails.index');
        Route::get('/pindetails/list', [PinDetailsController::class, 'list'])->name('pindetails.list');
        Route::post('/pindetails/store', [PinDetailsController::class, 'store'])->name('pindetails.store');
        Route::get('/pindetails/{id}/edit', [PinDetailsController::class, 'edit'])->name('pindetails.edit');
        Route::put('/pindetails/{id}', [PinDetailsController::class, 'update'])->name('pindetails.update');
        Route::delete('/pindetails/{id}', [PinDetailsController::class, 'destroy'])->name('pindetails.destroy');
    });
    Route::prefix('superadmin')->name('superadmin.')->group(function () {
        Route::get('/plan/index', [PlanController::class, 'index'])->name('plan.index');
        Route::get('/plan/list', [PlanController::class, 'list'])->name('plan.list');
        Route::post('/plan/store', [PlanController::class, 'store'])->name('plan.store');
        Route::get('/plan/{id}/edit', [PlanController::class, 'edit'])->name('plan.edit');
        Route::put('/plan/{id}', [PlanController::class, 'update'])->name('plan.update');
        Route::delete('/plan/{id}', [PlanController::class, 'destroy'])->name('plan.destroy');
    });
    // Every 'show' and 'create' entry below is named explicitly. Without them
    // Laravel derives the name from the URI, and all six custom-fields
    // resources collapsed onto the same 'custom-fields.show' /
    // 'custom-fields.create' name - which blocked route:cache and made
    // route('custom-fields.show') resolve to whichever registered last.
    Route::resource('superadmin/custom-fields', CustomFieldController::class)->names([
        'index' => 'custom_fields.index',
        'create' => 'custom_fields.create',
        'store' => 'custom_fields.store',
        'show' => 'custom_fields.show',
        'edit' => 'custom_fields.edit',
        'update' => 'custom_fields.update',
        'destroy' => 'custom_fields.destroy',
    ]);
    Route::resource('superadmin/pin-details/custom-fields', PinDetailsCustomFieldController::class)->names([
        'index' => 'pin-details.custom_fields.index',
        'create' => 'pin-details.custom_fields.create',
        'store' => 'pin-details.custom_fields.store',
        'show' => 'pin-details.custom_fields.show',
        'edit' => 'pin-details.custom_fields.edit',
        'update' => 'pin-details.custom_fields.update',
        'destroy' => 'pin-details.custom_fields.destroy',
    ]);
    Route::resource('superadmin/plan/custom-fields', PlanCustomFieldController::class)->names([
        'index' => 'plan.custom_fields.index',
        'create' => 'plan.custom_fields.create',
        'store' => 'plan.custom_fields.store',
        'show' => 'plan.custom_fields.show',
        'edit' => 'plan.custom_fields.edit',
        'update' => 'plan.custom_fields.update',
        'destroy' => 'plan.custom_fields.destroy',
    ]);
    Route::post('superadmin/pin-details/custom-fields/reorder', [PinDetailsCustomFieldController::class, 'reorder'])->name('pin-details.custom_fields.reorder');
    Route::post('superadmin/plan/custom-fields/reorder', [PlanCustomFieldController::class, 'reorder'])->name('plan.custom_fields.reorder');
    Route::post('/custom-fields/reorder', [CustomFieldController::class, 'reorder'])->name('custom_fields.reorder');
    Route::get('/permissions/data', [RolePermissionController::class, 'data'])->name('permissions.data');
    Route::post('/permissions/update', [RolePermissionController::class, 'update'])->name('permissions.update');
    Route::post('/permissions/store', [RolePermissionController::class, 'store'])->name('permissions.store');
    Route::put('/permissions/{id}', [RolePermissionController::class, 'updatePermission'])->name('updatepermissions.update');
    Route::delete('/permissions/{id}', [RolePermissionController::class, 'destroy'])->name('permissions.destroy');
    Route::post('/impersonate/{id}', [AuthController::class, 'impersonateLogin'])->name('impersonate.login');
    // Topup History Page
    Route::get('/superadmin/topup-history', [OrderController::class, 'orderHistory'])->name('superadmin.topup.history');
    Route::get('/superadmin/topup-history/data', [OrderController::class, 'orderHistoryData'])->name('superadmin.topup.history.data');
    Route::get('/superadmin/orders/{id}/view', [OrderController::class, 'viewOrder'])->name('superadmin.orders.view');
    Route::prefix('superadmin')->name('superadmin.')->group(function () {
        Route::get('paymentmethods/', [PaymentMethodController::class, 'index'])->name('paymentmethods.index');
        Route::get('paymentmethods/list', [PaymentMethodController::class, 'list'])->name('paymentmethods.list');
        Route::post('paymentmethods/store', [PaymentMethodController::class, 'store'])->name('paymentmethods.store');
        Route::get('paymentmethods/{id}/edit', [PaymentMethodController::class, 'edit'])->name('paymentmethods.edit');
        Route::put('paymentmethods/{id}', [PaymentMethodController::class, 'update'])->name('paymentmethods.update');
        Route::delete('paymentmethods/{id}', [PaymentMethodController::class, 'destroy'])->name('paymentmethods.destroy');
    });
    Route::get('superadmin/support/pending', [SuperAdminSupportController::class, 'Pending'])->name('superadmin.support.pending');
    Route::get('superadmin/support/pendinglist', [SuperAdminSupportController::class, 'supportList'])->name('superadmin.support.pendinglist');
    Route::post('superadmin/support/reply', [SuperAdminSupportController::class, 'supportReply'])->name('superadmin.support.reply');
    Route::get('/superadmin/support/approved', [SuperAdminSupportController::class, 'Approved'])->name('superadmin.support.approved');
    Route::get('/superadmin/support/approvedlist', [SuperAdminSupportController::class, 'approvedList'])->name('superadmin.support.approvedlist');
    Route::get('/superadmin/support/chat/{id}', [SuperAdminSupportController::class, 'showChat'])->name('superadmin.support.chat');
    Route::post('/superadmin/support/chat/send', [SuperAdminSupportController::class, 'sendReply'])->name('superadmin.support.chat.send');
    Route::post('/superadmin/support/update-status', [SuperAdminSupportController::class, 'updateStatus'])->name('superadmin.support.updateStatus');
    Route::prefix('superadmin')->name('superadmin.')->middleware(['auth'])->group(function () {
        Route::get('noticeboard', [NoticeBoardController::class, 'index'])->name('noticeboard.index');
        Route::get('noticeboard/data', [NoticeBoardController::class, 'data'])->name('newsevents.data');
        Route::post('noticeboard', [NoticeBoardController::class, 'store'])->name('noticeboard.store');
        Route::post('noticeboard/toggle-status', [NoticeBoardController::class, 'toggleStatus'])->name('noticeboard.toggleStatus');
        Route::get('noticeboard/{id}/edit', [NoticeBoardController::class, 'edit'])->name('noticeboard.edit');
        Route::post('noticeboard/{id}', [NoticeBoardController::class, 'update'])->name('noticeboard.update');
        Route::delete('noticeboard/{id}', [NoticeBoardController::class, 'destroy'])->name('noticeboard.destroy');
    });
    Route::get('superadmin/income/{slug}', [SuperAdminIncomeController::class, 'history'])
        ->name('superadmin.income.history');
    Route::get('superadmin/income/{slug}/data', [SuperAdminIncomeController::class, 'historyData'])
        ->name('superadmin.income.history.data');
    Route::get('/superadmin/income-report', [SuperAdminIncomeReportController::class, 'index'])->name('superadmin.income.report');
    Route::get('/superadmin/income-report/download', [SuperAdminIncomeReportController::class, 'downloadExcel'])->name('superadmin.income.report.download');
});
Route::get('/users/getSponsorName', [AuthController::class, 'getSponsorName'])->name('auth.getSponsorName');
// admin !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
// Admin Routes (protected by admin middleware)


Route::middleware(['auth:admin', 'admin.session', 'role:admin,subadmin'])->group(function () {



Route::get('admin/rank-income-report', [RankController::class, 'adminindex'])->name('admin.rank');
Route::get('/royalty-report',[RankController::class,'royaltyReport'])->name('admin.royalty.report');


    
        Route::prefix('admin/kyc')->name('admin.kyc.')->group(function () {
        Route::get('/', [AdminKycController::class, 'index'])->name('index');
        Route::get('/approved', [AdminKycController::class, 'approvedindex'])->name('approvedindex');
        Route::get('/rejected', [AdminKycController::class, 'rejectindex'])->name('rejectindex');
        Route::post('/{kycDetail}/approve', [AdminKycController::class, 'approve'])->name('approve');
        Route::post('/{kycDetail}/reject', [AdminKycController::class, 'reject'])->name('reject');
        Route::post('/{kycDetail}/delete-document', [AdminKycController::class, 'deleteDocument'])->name('deleteDocument');
    });

    
    
      // Login History Routes
    Route::get('admin/login-history', [LoginHistoryController::class, 'index'])->name('admin.login.history');
    Route::get('admin/login-history/export', [LoginHistoryController::class, 'export'])->name('admin.login.history.export');
    Route::delete('admin/login-history/clear', [LoginHistoryController::class, 'clearOldLogs'])->name('admin.login.history.clear');
    Route::get('admin/login-history/user/{id}', [LoginHistoryController::class, 'getUserHistory'])->name('admin.login.history.user');
    Route::get('admin/login-history/ajax-data', [LoginHistoryController::class, 'getAjaxData'])->name('admin.login.history.ajax');

    Route::prefix('admin')->name('admin.')->group(function () {

        Route::get('bonanza/', [BonanzaController::class, 'index'])->name('bonanza.index');

        Route::get('bonanza/create', [BonanzaController::class, 'create'])->name('bonanza.create');

        Route::post('bonanza/store', [BonanzaController::class, 'store'])->name('bonanza.store');

        Route::get('bonanza/{id}/edit', [BonanzaController::class, 'edit'])->name('bonanza.edit');

        Route::put('bonanza/{id}', [BonanzaController::class, 'update'])->name('bonanza.update');

        Route::delete('bonanza/{id}', [BonanzaController::class, 'destroy'])->name('bonanza.destroy');
    });

    Route::get('admin/bonanza/achievers', [BonanzaController::class, 'achieversList'])->name('admin.bonanza.achievers');

    //Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard/admin', [DashboardController::class, 'admindashboard'])->name('dashboard.admin');
    Route::get('/dashboard/admin/recentUserData', [DashboardController::class, 'recentUserData'])->name('admin.dashboard.recentUserData');
    Route::get('/adminusers', [UserController::class, 'adminindex'])->name('adminusers.index')->middleware('checkUserPermission:user,can_view');
    Route::get('/adminusers/users/data', [UserController::class, 'admingetData'])->name('adminusers.users.data');
    Route::get('admin/users/export', [UserController::class, 'exportUsersCsv'])->name('admin.users.export');
    Route::post('/adminusers/create', [UserController::class, 'admincreate'])->name('adminusers.create');
    // Read-only detail page. Declared before the {id}/edit route only for
    // grouping; the URIs do not overlap.
    Route::get('/adminusers/{id}/view', [UserController::class, 'adminshow'])
        ->name('adminusers.show')
        ->middleware('checkUserPermission:user,can_view');
    Route::get('/adminusers/{id}/edit', [UserController::class, 'adminedit'])->name('adminusers.edit');
    Route::put('/adminusers/{id}', [UserController::class, 'adminupdate'])->name('adminusers.update');
    Route::delete('/adminusers/{id}', [UserController::class, 'admindestroy']);
    Route::post('admin/impersonate/{id}', [AuthController::class, 'impersonateLogin'])->name('admin.impersonate.login');
    Route::get('/admin/change-password', [AdminAccountController::class, 'showChangePasswordForm'])->name('admin.change-password.form');
    Route::post('/admin/change-password', [AdminAccountController::class, 'updatePassword'])->name('admin.change-password.update');
    Route::get('/admin/profile', [UserController::class, 'adminprofile'])->name('admin.profile');
    Route::get('/admin/profile/edit', [ProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::post('/admin/profile/update', [ProfileController::class, 'update'])->name('admin.profile.update');
    // fund
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/matching', [ManualMatchingController::class, 'index'])->name('matching.index');
        Route::match(['get', 'post'], '/matching/filter', [ManualMatchingController::class, 'filterUsers'])->name('matching.filter');
        Route::post('/matching/distribute', [ManualMatchingController::class, 'distribute'])->name('matching.distribute');
    });
    Route::get('/admin/income-report', [AdminIncomeReportController::class, 'index'])->name('admin.income.report');
    Route::get('/admin/income-report/download', [AdminIncomeReportController::class, 'downloadExcel'])->name('admin.income.report.download');



    Route::prefix('admin')->middleware('auth:admin')->group(function () {

        Route::get('fund-requests', [AdminFranchiseFundRequestController::class, 'index'])->name('admin.franchise.fund.requests');

        Route::post('fund-requests/{id}/approve', [AdminFranchiseFundRequestController::class, 'approve'])->name('admin.franchise.fund.approve');

        Route::post('fund-requests/{id}/reject', [AdminFranchiseFundRequestController::class, 'reject'])->name('admin.franchise.fund.reject');
    });


// Admin Power Business Routes
Route::get('/admin/add-power', [AdminFundController::class, 'addPowerIndex'])->name('admin.power.index');
Route::post('/admin/add-power', [AdminFundController::class, 'addPower'])->name('admin.power.submit');

// Admin Power Business History Routes
Route::get('/admin/power-history', [AdminFundController::class, 'powerBusinessHistoryIndex'])->name('admin.powerhistory.index');
Route::get('/admin/power-history/list', [AdminFundController::class, 'powerBusinessHistoryList'])->name('admin.powerhistory.list');
Route::get('/admin/power-history/export', [AdminFundController::class, 'powerBusinessExportCSV'])->name('admin.powerhistory.export');


    Route::get('/admin/fund/add', [AdminFundController::class, 'addFundindex'])->name('admin.fund.index')->middleware('checkUserPermission:add fund,can_view');

    Route::post('admin/add-fund', [AdminFundController::class, 'addFund'])->name('admin.fund.add');

    Route::get('/admin/transferfundtransactions/index', [AdminFundController::class, 'transferfundtransactionsindex'])->name('admin.transferfundtransactions.index');

    Route::get('/admin/transferfundtransactions/export', [AdminFundController::class, 'transferFundExportCSV'])->name('admin.transferfundtransactions.export');


    Route::get('admin/transferfundtransactions/list', [AdminFundController::class, 'TransferFundtransactionslist'])->name('admin.transferfundtransactions.list');

    Route::get('/admin/fund/retrieve', [AdminFundController::class, 'RetrieveFundindex'])->name('admin.retrievefund.index')->middleware('checkUserPermission:Retrieve Fund,can_view');

    Route::post('/admin/fund/retrieve', [AdminFundController::class, 'retrieveFund'])->name('admin.fund.retrieve');

    Route::get('/admin/retrievefundtransactions/index', [AdminFundController::class, 'retrievefundtransactionsindex'])->name('admin.retrievefundtransactions.index')->middleware('checkUserPermission:Retrieve Fund History,can_view');

    Route::get('admin/retrievefundtransactions/list', [AdminFundController::class, 'retrievefundtransactionslist'])->name('admin.retrievefundtransactions.list');

    Route::get('admin/retrieve-fund-transactions/export', [AdminFundController::class, 'exportRetrieveFundCsv'])->name('admin.retrievefundtransactions.export');


    Route::get('/admin/transferfundtransactionsrequest/index', [AdminFundController::class, 'fundrequestindex'])->name('admin.transferfundtransactionsrequest.index')->middleware('checkUserPermission:Pending Fund Request,can_view');

    Route::get('admin/transferfundtransactionsrequest/list', [AdminFundController::class, 'fundrequestlist'])->name('admin.transferfundtransactionsrequest.list');

    Route::post('admin/transferfundtransactionsrequest/approve', [AdminFundController::class, 'approve'])->name('admin.transferfundtransactionsrequest.approve');

    Route::post('admin/transferfundtransactionsrequest/reject', [AdminFundController::class, 'reject'])->name('admin.transferfundtransactionsrequest.reject');

    // 🔥 Bulk approve (MULTI SELECT)
    Route::post('admin/fund-requests/bulk-approve', [AdminFundController::class, 'bulkApprove'])->name('admin.transferfundtransactionsrequest.bulkApprove');

    // 🔥 Bulk reject (MULTI SELECT)
    Route::post('admin/fund-requests/bulk-reject', [AdminFundController::class, 'bulkReject'])->name('admin.transferfundtransactionsrequest.bulkReject');

    // 🔥 CSV export (ONLY selected IDs)
    Route::get('/admin/fund-requests/export', [AdminFundController::class, 'exportCSV'])->name('admin.transferfundtransactionsrequest.export');

    Route::get('/admin/approvedfundrequest/index', [AdminFundController::class, 'approvefundrequestindex'])->name('admin.approvedfundrequest.index')->middleware('checkUserPermission:Approved Fund Request,can_view');

    Route::get('admin/approved-fund-request/export', [AdminFundController::class, 'approvedFundRequestExport'])->name('admin.approvedfundrequest.export');


    Route::get('admin/approvedfundrequest/list', [AdminFundController::class, 'approvefundrequestlist'])->name('admin.approvedfundrequest.list');

    Route::get('/admin/cancelledfundrequest/index', [AdminFundController::class, 'cancelledfundrequestindex'])->name('admin.cancelledfundrequest.index')->middleware('checkUserPermission:Cancelled Fund Request,can_view');

    Route::get('admin/cancelledfundrequest/list', [AdminFundController::class, 'cancelledfundrequestlist'])->name('admin.cancelledfundrequest.list');
    Route::get('admin/cancelled-fund-request/export', [AdminFundController::class, 'cancelledFundRequestExport'])->name('admin.cancelledfundrequest.export');


    Route::get('/admin/fund-convert/history', [AdminFundController::class, 'fundConvertHistory'])->name('admin.fund.convert.history')->middleware('checkUserPermission:Fund Convert History,can_view');

    Route::get('/admin/fund-convert/history/data', [AdminFundController::class, 'fundConvertHistoryData'])->name('admin.fund.convert.history.data');

    Route::get('/admin/fundrequest/{id}/detail', [AdminFundController::class, 'fundrequestDetail'])->name('admin.transferfundtransactionsrequest.detail');





  
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/categories', [CategoryController::class, 'index'])->name('category.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('category.store');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('category.delete');
    Route::post('/categories/toggle-bogo/{id}', [CategoryController::class, 'toggleBogo'])->name('category.toggleBogo');
});

    Route::prefix('admin')->group(function () {

        Route::get('/sub-categories', [SubCategoryController::class, 'index'])
            ->name('admin.subcategory.index');

        Route::post('/sub-categories', [SubCategoryController::class, 'store'])
            ->name('admin.subcategory.store');

        Route::put('/sub-categories/{id}', [SubCategoryController::class, 'update'])
            ->name('admin.subcategory.update');

        Route::delete('/sub-categories/{id}', [SubCategoryController::class, 'destroy'])
            ->name('admin.subcategory.delete');
    });

    // routes/web.php

    Route::get('/admin/get-subcategories/{category}', function ($category) {
        return \App\Models\SubCategory::where('category_id', $category)
            ->where('status', 1)
            ->get();
    })->name('admin.get.subcategories');



// View page route
Route::get('/admin/rank-rewards', [RankRewardController::class, 'index'])->name('admin.ranks.index');

// Update backend route
Route::post('/admin/rank-rewards/update-field', [RankRewardController::class, 'updateRankField'])->name('admin.ranks.updateField');



    Route::prefix('admin')->group(function () {

        Route::get('/products', [ProductController::class, 'index'])
            ->name('admin.product.index');



        Route::post('/products', [ProductController::class, 'store'])
            ->name('admin.product.store');

        Route::get('/products/list', [ProductController::class, 'list'])
            ->name('admin.product.list');



            Route::put('/product/update/{id}', [ProductController::class,'update'])->name('admin.product.update');

            Route::delete('/product/delete/{id}', [ProductController::class,'destroy'])->name('admin.product.delete');
    });

    Route::get('/admin/stock', [ProductController::class, 'productstock'])
        ->name('admin.stock.index');



    Route::prefix('admin')->group(function () {

        // ✅ LIST PAGE
        Route::get('/franchise/list', [FranchiseController::class, 'index'])
            ->name('admin.franchise.index');

        // ✅ DATATABLE DATA
        Route::get('/franchise/data', [FranchiseController::class, 'getData'])
            ->name('admin.franchise.data');

        // ✅ CREATE FORM
        Route::get('/franchises/create', [FranchiseController::class, 'create'])
            ->name('admin.franchise.create');

        // ✅ STORE (POST ONLY)
        Route::post('/franchises', [FranchiseController::class, 'store'])
            ->name('admin.franchise.store');

        // ✅ IMPERSONATE LOGIN
        Route::post('/franchise/login/{id}', [FranchiseController::class, 'impersonateLogin'])
            ->name('admin.franchise.impersonate');

        // ✅ DASHBOARD
        Route::get('/franchises/{id}/dashboard', [FranchiseController::class, 'dashboard'])
            ->name('admin.franchise.dashboard');


        Route::get('admin/franchise/{id}/edit', [FranchiseController::class, 'edit'])->name('admin.franchise.edit');
        Route::put('admin/franchise/{id}', [FranchiseController::class, 'update'])->name('admin.franchise.update');
    });




    // withdrwals


    /* ================= PENDING WITHDRAWALS ================= */

    Route::get(
        '/admin/withdrwalstransactionsrequest/index',
        [AdminWithdrwalsController::class, 'withdrwalsrequestindex']
    )->name('admin.withdrwalstransactionsrequest.index')
        ->middleware('checkUserPermission:Pending Withdrawal Requests,can_view');

    Route::get(
        '/admin/withdrwalstransactionsrequest/list',
        [AdminWithdrwalsController::class, 'withdrwalsrequestlist']
    )->name('admin.withdrwalstransactionsrequest.list');

    Route::get(
        '/admin/withdrwalsrequest/{id}/detail',
        [AdminWithdrwalsController::class, 'withdrwalsrequestDetail']
    )->name('admin.withdrwalsrequest.detail');

    /* ================= SINGLE ACTIONS ================= */

    Route::post(
        '/admin/withdrwalstransactionsrequest/approve',
        [AdminWithdrwalsController::class, 'approve']
    )->name('admin.withdrwalstransactionsrequest.approve');

    Route::post(
        '/admin/withdrwalstransactionsrequest/reject',
        [AdminWithdrwalsController::class, 'reject']
    )->name('admin.withdrwalstransactionsrequest.reject');

    /* ================= BULK ACTIONS ================= */

    Route::post(
        '/admin/withdrwalstransactionsrequest/bulk-approve',
        [AdminWithdrwalsController::class, 'bulkApprove']
    )->name('admin.withdrwalsrequest.bulkApprove');

    Route::post(
        '/admin/withdrwalstransactionsrequest/bulk-reject',
        [AdminWithdrwalsController::class, 'bulkReject']
    )->name('admin.withdrwalsrequest.bulkReject');

    Route::get(
        '/admin/withdrwalstransactionsrequest/export',
        [AdminWithdrwalsController::class, 'exportCSV']
    )->name('admin.withdrwalsrequest.export');

    /* ================= APPROVED WITHDRAWALS ================= */

    Route::get('/admin/approvedwithdrwalsrequest/index', [AdminWithdrwalsController::class, 'approvewithdrwalsrequestindex'])->name('admin.approvedwithdrwalsrequest.index')->middleware('checkUserPermission:Approved Withdrawal Requests,can_view');

    Route::get('/admin/approvedwithdrwalsrequest/list', [AdminWithdrwalsController::class, 'approvewithdrwalsrequestlist'])->name('admin.approvedwithdrwalsrequest.list');

    Route::get('/admin/approvedwithdrwalsrequest/export', [AdminWithdrwalsController::class, 'exportApprovedCSV'])->name('admin.approvedwithdrwalsrequest.export');


    /* ================= CANCELLED WITHDRAWALS ================= */

    Route::get('/admin/cancelledwithdrwalsrequest/index', [AdminWithdrwalsController::class, 'cancelledwithdrwalsrequestindex'])->name('admin.cancelledwithdrwalsrequest.index')->middleware('checkUserPermission:Cancelled Withdrawal Requests,can_view');

    Route::get('/admin/cancelledwithdrwalsrequest/list', [AdminWithdrwalsController::class, 'cancelledwithdrwalsrequestlist'])->name('admin.cancelledwithdrwalsrequest.list');

    Route::get('/admin/withdrawals/cancelled/export-csv', [AdminWithdrwalsController::class, 'exportCancelledWithdrawalsCsv'])->name('admin.cancelled.withdraw.csv');
    
    
    



    // Principal Withdrawal
    Route::get('/admin/principalwithdrwalstransactionsrequest/index', [AdminWithdrwalsController::class, 'principalwithdrwalsrequestindex'])->name('admin.principalwithdrwalstransactionsrequest.index')->middleware('checkUserPermission:Pending Principal Withdrawal Requests,can_view');
    Route::get('/admin/principalwithdrwalstransactionsrequest/list', [AdminWithdrwalsController::class, 'principalwithdrwalsrequestlist'])->name('admin.principalwithdrwalstransactionsrequest.list');
    Route::get('/admin/principalwithdrwalsrequest/{id}/detail', [AdminWithdrwalsController::class, 'principalwithdrwalsrequestDetail'])->name('admin.principalwithdrwalsrequest.detail');
    Route::post('/admin/principalwithdrwalstransactionsrequest/approve', [AdminWithdrwalsController::class, 'principalapprove'])->name('admin.principalwithdrwalstransactionsrequest.approve');
    Route::post('/admin/principalwithdrwalstransactionsrequest/reject', [AdminWithdrwalsController::class, 'principalreject'])->name('admin.principalwithdrwalstransactionsrequest.reject');
    Route::get('/admin/approvedprincipalwithdrwalsrequest/index', [AdminWithdrwalsController::class, 'approveprincipalwithdrwalsrequestindex'])->name('admin.approvedprincipalwithdrwalsrequest.index')->middleware('checkUserPermission:Approved Principal Withdrawal Requests,can_view');
    Route::get('/admin/approvedprincipalwithdrwalsrequest/list', [AdminWithdrwalsController::class, 'approveprincipalwithdrwalsrequestlist'])->name('admin.approvedprincipalwithdrwalsrequest.list');
    Route::get('/admin/cancelledprincipalwithdrwalsrequest/index', [AdminWithdrwalsController::class, 'cancelledprincipalwithdrwalsrequestindex'])->name('admin.cancelledprincipalwithdrwalsrequest.index')->middleware('checkUserPermission:Cancelled Principal Withdrawal Requests,can_view');
    Route::get('/admin/cancelledprincipalwithdrwalsrequest/list', [AdminWithdrwalsController::class, 'cancelledprincipalwithdrwalsrequestlist'])->name('admin.cancelledprincipalwithdrwalsrequest.list');


    Route::resource('admin/custom-fields', AdminCustomFieldController::class)
        ->names([
            'index' => 'admin.custom_fields.index',
            'create' => 'admin.custom_fields.create',
            'store' => 'admin.custom_fields.store',
            'show' => 'admin.custom_fields.show',
            'edit' => 'admin.custom_fields.edit',
            'update' => 'admin.custom_fields.update',
            'destroy' => 'admin.custom_fields.destroy',
        ])
        ->middleware('checkUserPermission:Registion Form,can_view');
    Route::post('admin/custom-fields/reorder', [AdminCustomFieldController::class, 'reorder'])->name('admin.custom_fields.reorder');
    // mail templates
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('mail-templates', [AdminMailTemplateController::class, 'index'])->name('mailtemplates.index')->middleware('checkUserPermission:Mail Template,can_view');
        Route::get('mail-templates/data', [AdminMailTemplateController::class, 'getData'])->name('mailtemplates.data');
        Route::get('mail-templates/{mailTemplate}/preview', [AdminMailTemplateController::class, 'preview'])->name('mailtemplates.preview');
        Route::get('mail-templates/create', [AdminMailTemplateController::class, 'create'])->name('mailtemplates.create');
        Route::post('mail-templates', [AdminMailTemplateController::class, 'store'])->name('mailtemplates.store');
        Route::get('mail-templates/{mailTemplate}/edit', [AdminMailTemplateController::class, 'edit'])->name('mailtemplates.edit');
        Route::put('mail-templates/{mailTemplate}', [AdminMailTemplateController::class, 'update'])->name('mailtemplates.update');
        Route::delete('mail-templates/{mailTemplate}', [AdminMailTemplateController::class, 'destroy'])->name('mailtemplates.destroy');
    });
    // pin details
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/pindetails/index', [AdminPinDetailsController::class, 'index'])->name('pindetails.index')->middleware('checkUserPermission:Pin Details List,can_view');
        Route::get('/pindetails/list', [AdminPinDetailsController::class, 'list'])->name('pindetails.list');
        Route::post('/pindetails/store', [AdminPinDetailsController::class, 'store'])->name('pindetails.store');
        Route::get('/pindetails/{id}/edit', [AdminPinDetailsController::class, 'edit'])->name('pindetails.edit');
        Route::put('/pindetails/{id}', [AdminPinDetailsController::class, 'update'])->name('pindetails.update');
        Route::delete('/pindetails/{id}', [AdminPinDetailsController::class, 'destroy'])->name('pindetails.destroy');
    });
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/plan/index', [AdminPlanController::class, 'index'])->name('plan.index')->middleware('checkUserPermission:Plan List,can_view');
        Route::get('/plan/list', [AdminPlanController::class, 'list'])->name('plan.list');
        Route::post('/plan/store', [AdminPlanController::class, 'store'])->name('plan.store');
        Route::get('/plan/{id}/edit', [AdminPlanController::class, 'edit'])->name('plan.edit');
        Route::put('/plan/{id}', [AdminPlanController::class, 'update'])->name('plan.update');
        Route::delete('/plan/{id}', [AdminPlanController::class, 'destroy'])->name('plan.destroy');
    });
    Route::resource('admin/pin-details/custom-fields', AdminPinDetailsCustomFieldController::class)->names([
        'index' => 'admin.pin-details.custom_fields.index',
        'create' => 'admin.pin-details.custom_fields.create',
        'store' => 'admin.pin-details.custom_fields.store',
        'show' => 'admin.pin-details.custom_fields.show',
        'edit' => 'admin.pin-details.custom_fields.edit',
        'update' => 'admin.pin-details.custom_fields.update',
        'destroy' => 'admin.pin-details.custom_fields.destroy',
    ])->middleware('checkUserPermission:Pin Details Add Custom Fields,can_view');
    Route::resource('admin/plan/custom-fields', AdminPlanCustomFieldController::class)->names([
        'index' => 'admin.plan.custom_fields.index',
        'create' => 'admin.plan.custom_fields.create',
        'store' => 'admin.plan.custom_fields.store',
        'show' => 'admin.plan.custom_fields.show',
        'edit' => 'admin.plan.custom_fields.edit',
        'update' => 'admin.plan.custom_fields.update',
        'destroy' => 'admin.plan.custom_fields.destroy',
    ])->middleware('checkUserPermission:Plan Add Custom Fields,can_view');
    Route::post('admin/pin-details/custom-fields/reorder', [AdminPinDetailsCustomFieldController::class, 'reorder'])->name('admin.pin-details.custom_fields.reorder');
    Route::post('admin/plan/custom-fields/reorder', [AdminPlanCustomFieldController::class, 'reorder'])->name('admin.plan.custom_fields.reorder');
    // Topup History Page
    Route::get('/admin/topup-history', [AdminOrderController::class, 'orderHistory'])->name('admin.topup.history')->middleware('checkUserPermission:Order History,can_view');
    Route::get('/admin/topup-history/data', [AdminOrderController::class, 'orderHistoryData'])->name('admin.topup.history.data');
    Route::get('/admin/orders/{id}/view', [AdminOrderController::class, 'viewOrder'])->name('admin.orders.view');


    Route::get('/admin/club-achievers', [AdminOrderController::class, 'getClubAchievers'])->name('admin.club.achievers');
    Route::post('/admin/distribute-club-income', [AdminOrderController::class, 'distributeClubIncome'])->name('admin.club.distribute');
   Route::get('/orders/print-invoice/{id}', [AdminOrderController::class, 'printSingleInvoice'])->name('admin.orders.print-invoice');
   


Route::get('/admin/topup', [AdminOrderController::class, 'admintopup'])->name('admin.topup');
Route::post('/admin/topup/store', [AdminOrderController::class, 'adminAddTopup'])->name('admin.topup.store');
Route::post('/admin/check-user', [AdminOrderController::class, 'checkUser'])->name('admin.check.user');

    // Product Order Routes (નામ સુધારેલ છે)
    Route::get('/admin/product-orders/{status}', [AdminOrderController::class, 'productOrderPage'])->name('admin.product.orders');
    Route::get('/admin/product-orders-data/{status}', [AdminOrderController::class, 'productOrderData'])->name('admin.product.orders.data');
    Route::get('/admin/orders/view/{id}', [AdminOrderController::class, 'productviewOrder'])->name('admin.productorders.view');
    Route::post('/admin/orders/update-status/{id}', [AdminOrderController::class, 'productupdateStatus'])->name('admin.productorders.update_status');


    Route::prefix('admin')->middleware('admin.session')->group(function () {

        Route::get('admin/franchise/orders', [AdminFranchiseOrderController::class, 'franchiseOrders'])->name('admin.franchise.orders');

        Route::get('admin/franchise/orders/data', [AdminFranchiseOrderController::class, 'franchiseOrdersData'])->name('admin.franchise.orders.data');

        Route::get('admin/franchise/orders/{id}', [AdminFranchiseOrderController::class, 'viewFranchiseOrder'])->name('admin.franchise.orders.view');

        Route::post('franchise-orders/{id}/approve', [AdminFranchiseOrderController::class, 'approveFranchiseOrder'])->name('admin.franchise.orders.approve');

        Route::post('franchise-orders/{id}/reject', [AdminFranchiseOrderController::class, 'rejectFranchiseOrder'])->name('admin.franchise.orders.reject');
    });


    // Admin - Reward & Royalty Achievers
    Route::prefix('admin/rewards')->group(function () {
        Route::get('/achievers', [AdminOrderController::class, 'rewardAchievers'])->name('admin.reward.achievers');
        Route::get('/achievers/data', [AdminOrderController::class, 'rewardAchieversData'])->name('admin.reward.achievers.data');

        Route::get('/royalty', [AdminOrderController::class, 'royaltyAchievers'])->name('admin.royalty.achievers');
        Route::get('/royalty/data', [AdminOrderController::class, 'royaltyAchieversData'])->name('admin.royalty.achievers.data');
    });




    Route::get('/admin/company-info', [AdminCompanyInfoController::class, 'index'])->name('admin.companyinfo.index')->middleware('checkUserPermission:Company Info,can_view');
    Route::get('/admin/company-info/data', [AdminCompanyInfoController::class, 'getData'])->name('admin.companyinfo.data');
    Route::post('/admin/company-info/store', [AdminCompanyInfoController::class, 'store'])->name('admin.companyinfo.store');
    Route::post('/admin/company-info/update', [AdminCompanyInfoController::class, 'update'])->name('admin.companyinfo.update');
    Route::get('admin/advance-info', [AdminAdvanceInfoController::class, 'index'])->name('admin.advanceinfo.index')->middleware('checkUserPermission:Advance Info,can_view');
    Route::get('admin/advance-info/data', [AdminAdvanceInfoController::class, 'getData'])->name('admin.advanceinfo.data');
    Route::post('admin/advance-info/store', [AdminAdvanceInfoController::class, 'store'])->name('admin.advanceinfo.store');
    Route::get('admin/advance-info/edit/{id}', [AdminAdvanceInfoController::class, 'edit'])->name('admin.advanceinfo.edit'); // THE CRITICAL EDIT ROUTE
    Route::delete('admin/advance-info/delete/{id}', [AdminAdvanceInfoController::class, 'destroy'])->name('admin.advanceinfo.delete');
    Route::put('admin/advance-info/update/{id}', [AdminAdvanceInfoController::class, 'update'])->name('admin.advance-info.update');
    // matching Calculation

    // Binary Matching Closing → run cron, then open Manual Matching page
    // (same pattern as Auto Withdrawal opening its list instead of back()).
    Route::get('/admin/run-matching-closing', function () {
        try {
            Artisan::call('matching:closing');
            return redirect()->route('admin.matching.index')
                ->with('success', 'Binary matching closing executed successfully.');
        } catch (\Throwable $e) {
            return redirect()->route('admin.matching.index')
                ->with('error', 'Binary matching failed: ' . $e->getMessage());
        }
    })->name('admin.command.matching.closing');

    // Rank Achievement Closing → run cron, then open Rank Achievers report
    Route::get('/admin/run-rank-closing', function () {
        try {
            Artisan::call('rank:closing');
            return redirect()->route('admin.rank')
                ->with('success', 'Rank achievement closing executed successfully.');
        } catch (\Throwable $e) {
            return redirect()->route('admin.rank')
                ->with('error', 'Rank closing failed: ' . $e->getMessage());
        }
    })->name('admin.command.rank.closing');

    // Rank Income Distribution (2% CTO)
    Route::get('/admin/run-rank-distribute', function () {
        Artisan::call('rank:distribute-income');
        return redirect()->route('admin.rank')
            ->with('success', 'Rank income distribution executed successfully.');
    })->name('admin.command.rank.distribute.income');

    // Auto Withdrawal opens the pending-withdrawal list. Excel, PDF, and Print
    // are the buttons already on that page.
Route::get('/admin/run-auto-withdrawal', function () {
    return redirect()->route('admin.withdrwalstransactionsrequest.index');
})->name('admin.command.withdrawal.auto');


// Royalty Income → run cron, then open Royalty Achievers report
Route::get('/admin/run-monthly-income-release', function () {
    try {
        Artisan::call('royalty:income');
        return redirect()->route('admin.royalty.report')
            ->with('success', 'Royalty income release executed successfully.');
    } catch (\Throwable $e) {
        return redirect()->route('admin.royalty.report')
            ->with('error', 'Royalty income failed: ' . $e->getMessage());
    }
})->name('admin.command.monthly.income.release');





    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('paymentmethods/', [AdminPaymentMethodController::class, 'index'])->name('paymentmethods.index')->middleware('checkUserPermission:Payment Methods,can_view');
        Route::get('paymentmethods/list', [AdminPaymentMethodController::class, 'list'])->name('paymentmethods.list');
        Route::post('paymentmethods/store', [AdminPaymentMethodController::class, 'store'])->name('paymentmethods.store');
        Route::get('paymentmethods/{id}/edit', [AdminPaymentMethodController::class, 'edit'])->name('paymentmethods.edit');
        Route::put('paymentmethods/{id}', [AdminPaymentMethodController::class, 'update'])->name('paymentmethods.update');
        Route::delete('paymentmethods/{id}', [AdminPaymentMethodController::class, 'destroy'])->name('paymentmethods.destroy');
    });
    Route::get('admin/support/pending', [AdminSupportController::class, 'Pending'])->name('admin.support.pending')->middleware('checkUserPermission:Support List,can_view');
    Route::get('admin/support/pendinglist', [AdminSupportController::class, 'supportList'])->name('admin.support.pendinglist');
    Route::post('admin/support/reply', [AdminSupportController::class, 'supportReply'])->name('admin.support.reply');
    Route::get('/admin/support/approved', [AdminSupportController::class, 'Approved'])->name('admin.support.approved');
    Route::get('/admin/support/approvedlist', [AdminSupportController::class, 'approvedList'])->name('admin.support.approvedlist');
    Route::get('/admin/support/chat/{id}', [AdminSupportController::class, 'showChat'])->name('admin.support.chat');
    Route::post('/admin/support/chat/send', [AdminSupportController::class, 'sendReply'])->name('admin.support.chat.send');
    Route::post('/admin/support/update-status', [AdminSupportController::class, 'updateStatus'])->name('admin.support.updateStatus');
    Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
        Route::get('noticeboard', [AdminNoticeBoardController::class, 'index'])->name('noticeboard.index')->middleware('checkUserPermission:Notice Board,can_view');
        Route::get('noticeboard/data', [AdminNoticeBoardController::class, 'data'])->name('newsevents.data');
        Route::post('noticeboard', [AdminNoticeBoardController::class, 'store'])->name('noticeboard.store');
        Route::post('noticeboard/toggle-status', [AdminNoticeBoardController::class, 'toggleStatus'])->name('noticeboard.toggleStatus');
        Route::get('noticeboard/{id}/edit', [AdminNoticeBoardController::class, 'edit'])->name('noticeboard.edit');
        Route::post('noticeboard/{id}', [AdminNoticeBoardController::class, 'update'])->name('noticeboard.update');
        Route::delete('noticeboard/{id}', [AdminNoticeBoardController::class, 'destroy'])->name('noticeboard.destroy');
    });




    Route::prefix('admin')->name('admin.')->middleware(['auth:admin'])->group(function () {

        Route::get('sub-admins', [SubAdminController::class, 'index'])->name('subadmins');

        Route::post('sub-admin/store', [SubAdminController::class, 'store'])->name('subadmin.store');

        Route::post('sub-admin/update', [SubAdminController::class, 'update'])->name('subadmin.update');

        Route::get('sub-admin/{id}', [SubAdminController::class, 'edit'])->name('subadmin.edit');
    });



    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('income/{slug}/history', [AdminIncomeController::class, 'incomeHistory'])->name('income.history');
        Route::get('income/{slug}/history-data', [AdminIncomeController::class, 'incomeHistoryData'])->name('income.history.data');
        Route::get('income/{slug}/download', [AdminIncomeController::class, 'downloadIncomeExcel'])->name('income.download');
    });
});
// user !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
Route::middleware(['auth:web', 'role:user,web', 'checkSiteStatus'])->group(function () {


    Route::get('/dashboard/user', [DashboardController::class, 'userdashboard'])->name('dashboard.user');
    Route::get('/dashboard/user/recentbinaryData', [DashboardController::class, 'recentbinaryData'])->name('user.dashboard.recentbinaryData');
    Route::get('/dashboard/user/recentEarningsData', [DashboardController::class, 'recentEarningsData'])->name('user.dashboard.recentEarningsData');
    Route::get('/user/profile', [UserController::class, 'userprofile'])->name('user.profile');
    Route::get('/user/profile/edit', [ProfileController::class, 'edit'])->name('user.profile.edit');
    Route::post('/user/profile/update', [ProfileController::class, 'update'])->name('user.profile.update');
       Route::post('/profile/update-password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('user.profile.update.password');
    Route::post('/user/default-side', [UserController::class, 'updateDefaultSide'])->name('user.update.default.side');
    Route::get('/kyc', [UserKycController::class, 'index'])->name('user.kyc.show');
    
        Route::get('/user/id-card', [App\Http\Controllers\UserIdCardController::class, 'show'])->name('user.idcard');
    Route::get('/user/id-card/download', [App\Http\Controllers\UserIdCardController::class, 'download'])->name('user.idcard.download');
    
    Route::post('/kyc/personal/submit', [UserKycController::class, 'submitPersonal'])->name('user.kyc.personal.submit');
    Route::post('/kyc/identity/submit', [UserKycController::class, 'submitIdentity'])->name('user.kyc.identity.submit');
    Route::post('/kyc/pan/submit', [UserKycController::class, 'submitPan'])->name('user.kyc.pan.submit');
    Route::post('/kyc/bank/submit', [UserKycController::class, 'submitBank'])->name('user.kyc.bank.submit');
    // Route::get('/dream-car-bonus', [DreamCarBonusController::class, 'index'])->name('user.dream_car_bonus');
    // Route::get('/leadership-circle-bonus', [LeadershipCircleBonusController::class, 'index'])->name('user.leadership_circle_bonus');
    Route::get('/user/fund/add', [UserFundController::class, 'addFundindex'])->name('user.fund.index')->middleware('checkUserPermission:add fund,can_view');
    Route::post('/user/fund/new_fund_add', [UserFundController::class, 'newDappFund'])->name('user.fund.dapp');
    Route::get('/add-fund/history', [UserFundController::class, 'addfundHistory'])->name('user.addfund.history')->middleware('checkUserPermission:add fund history,can_view');
    Route::get('/add-fund/history-data', [UserFundController::class, 'addfundHistoryData'])->name('user.addfund.history.data');
    Route::get('/user/fundrequest/add', [UserFundController::class, 'addfundrequestindex'])->name('user.fundrequest.index')->middleware('checkUserPermission:add fund request,can_view');
    Route::get('user/get-payment-types', [UserFundController::class, 'getChildPaymentMethods'])->name('user.getpaymenttypes.index');
    Route::post('/user/fundrequest/store', [UserFundController::class, 'storefundrequest'])->name('user.fundrequest.store');
    Route::get('/fund-request/history', [UserFundController::class, 'fundrequesthistory'])->name('user.fundrequest.history')->middleware('checkUserPermission:fund request history,can_view');
    Route::get('/fund-request/history-data', [UserFundController::class, 'fundrequesthistoryData'])->name('user.fundrequest.history.data');
    Route::get('/user/fund/transfer', [UserFundController::class, 'FundTransferindex'])->name('user.fundtransfer.index')->middleware('checkUserPermission:fund transfer,can_view');
    Route::post('/user/transfer-fund', [UserFundController::class, 'FundTransfer'])->name('user.fund.transfer');
    Route::get('/fund-transfer/history', [UserFundController::class, 'fundTransferHistory'])->name('user.fundtransfer.history')->middleware('checkUserPermission:fund transfer history,can_view');
    Route::get('/fund-transfer/history-data', [UserFundController::class, 'fundTransferHistoryData'])->name('user.fundtransfer.history.data');
    Route::get('/user/walletinternal/transfer', [UserFundController::class, 'internalWalletIndex'])->name('user.walletinternal.index')->middleware('checkUserPermission:fund convert,can_view');
    Route::post('/user/wallet/internal-transfer', [UserFundController::class, 'internalWalletTransfer'])->name('user.walletinternal.transfer');
    Route::get('/user/fund-convert/history', [UserFundController::class, 'fundConvertHistory'])->name('fund.convert.history')->middleware('checkUserPermission:fund convert history,can_view');
    Route::get('/user/fund-convert/history/data', [UserFundController::class, 'fundConvertHistoryData'])->name('fund.convert.history.data');
    // invest
    Route::get('/user/topup/index', [InvestController::class, 'topupIndex'])->name('user.topup.index')->middleware('checkUserPermission:Topup,can_view');
    Route::get('/user/Subcription/index', [InvestController::class, 'SubcriptionIndex'])->name('user.subcription.index')->middleware('checkUserPermission:Topup,can_view');
    Route::post('/user/subcription/store', [InvestController::class, 'subcription'])->name('user.subcription.store');
    Route::post('/user/topup/store', [InvestController::class, 'store'])->name('user.topup.store');
    Route::post('/user/topup/apistore', [InvestController::class, 'invest_api'])->name('user.topup.apistore');
    Route::get('/user/order/history', [InvestController::class, 'orderHistory'])->name('user.order.history')->middleware('checkUserPermission:Orders,can_view');
    Route::get('/user/order/history/data', [InvestController::class, 'orderHistoryData'])->name('user.order.history.data');
    Route::post('/user/topup/invest', [InvestController::class, 'invest'])->name('user.invest.store');
   
    Route::get('/user/product-orders', [InvestController::class,'productHistory'])->name('user.product.history');

Route::get('/user/product-orders/data', [InvestController::class,'productHistoryData'])->name('user.product.history.data');




    // Withdrawal
    Route::get('user/payout', [UserPayoutController::class, 'index'])->name('user.payout.index')->middleware('checkUserPermission:Withdrawal_view,can_view');
    Route::post('user/payout/store', [UserPayoutController::class, 'store'])->name('user.payout.store');
    Route::get('user/my-withdrawals', [UserPayoutController::class, 'myWithdrawals'])->name('user.withdrawals')->middleware('checkUserPermission:Withdrawal Report,can_view');
    Route::get('user/my-withdrawals/list', [UserPayoutController::class, 'myWithdrawalsList'])->name('user.withdrawals.list');
    Route::get('/income/details/{slug}', [IncomeController::class, 'incomePage'])->name('user.income.details')->middleware('checkUserPermission:User Income,can_view');
    Route::get('/income/details/{slug}/history', [IncomeController::class, 'incomeHistory'])->name('user.income.history');
    Route::get('user/accountsettings/create', [CompanyPaymentMethodController::class, 'create'])->name('user.accountsettings.create');
    Route::post('user/accountsettings/store', [CompanyPaymentMethodController::class, 'store'])->name('user.accountsettings.store');
    Route::delete('user/account-settings/delete/{id}', [CompanyPaymentMethodController::class, 'delete'])->name('user.accountsettings.delete');
    Route::post('user/account-settings/set-default', [CompanyPaymentMethodController::class, 'setDefault'])->name('user.accountsettings.setdefault');
    // reward
    Route::get('/user/rank-qualification', [RankController::class, 'index'])->name('user.rank.qualification')->middleware('checkUserPermission:Reward,can_view');
    Route::get('/user/rank-rankrise', [RankController::class, 'rewards'])->name('user.rank.rankrise')->middleware('checkUserPermission:Reward,can_view');
    Route::get('/user/rank-achievement', [RankController::class, 'rankAchievement'])->name('user.rank.achievement')->middleware('checkUserPermission:Reward,can_view');
    Route::get('/user/royalty-achievement', [RankController::class, 'royalty'])->name('user.royalty.achievement')->middleware('checkUserPermission:Reward,can_view');
    Route::get('user/support', [SupportController::class, 'index'])->name('user.support.index')->middleware('checkUserPermission:Support,can_view');
    Route::post('user/support', [SupportController::class, 'store'])->name('user.support.store');
    Route::get('user/support/supportticketdata', [SupportController::class, 'supportTicketData'])->name('user.support.supportticketdata');
    Route::get('user/support/chat/{id}', [SupportController::class, 'viewChat'])->name('user.support.viewChat');
    Route::post('user/support/chat/reply/{id}', [SupportController::class, 'sendReply'])->name('user.support.reply');
    Route::post('user/support/chat/close/{id}', [SupportController::class, 'closeTicket'])->name('user.support.close');
    Route::get('/user/income-report', [IncomeReportController::class, 'index'])->name('user.income.report');
    Route::get('/user/income-report/download', [IncomeReportController::class, 'downloadExcel'])->name('user.income.report.download');
    Route::get('/direct-users', [GenealogyController::class, 'index'])->name('user.genealogy.direct-users');
    Route::get('/left-team', [GenealogyController::class, 'leftteamindex'])->name('user.genealogy.left-team');
    Route::get('/right-team', [GenealogyController::class, 'rightteamindex'])->name('user.genealogy.right-team');
    Route::get('/team-users', [GenealogyController::class, 'teamList'])->name('user.team.users');
    Route::get('user/universal-tree', [GenealogyController::class, 'universalTree'])->name('user.universal.tree');
    Route::get('/principal-withdrawal', [UserPayoutController::class, 'PrincipalWithdrawalindex'])->name('user.principal.withdraw.index');
    Route::post('/principal-withdrawal', [UserPayoutController::class, 'PrincipalWithdrawalstore'])->name('user.principal.withdraw.store');
    Route::get('user/principal-withdrawal', [UserPayoutController::class, 'MyPrincipalWithdrawal'])->name('user.principal.withdraw');
    Route::get('user/principal-withdrawal/list', [UserPayoutController::class, 'PrincipalWithdrawalList'])->name('user.principal.withdraw.list');
    Route::get('/user/get-username', [UserController::class, 'getUserName'])->name('user.getUserName');
    // Show the booster topup form
    Route::get('/booster-topup', [BoosterPackageController::class, 'boosterTopupView'])->name('user.booster.topup');
    // Handle booster topup purchase
    Route::post('/booster-topup', [BoosterPackageController::class, 'buyBoosterPackage'])->name('user.booster.topup.submit');
    Route::get('/booster/tree', [BoosterPackageController::class, 'boosterTree'])->name('user.booster.tree');
    Route::get('/news-events', [NewsEventsController::class, 'index'])->name('user.newsevents.index');
    Route::get('/news-events/{id}', [NewsEventsController::class, 'show'])->name('user.news_events.show');
    Route::get('/tree-view', [TreeViewController::class, 'showTree'])->name('user.tree.view');
    Route::get('/user/tree/fetch-subtree', [TreeViewController::class, 'fetchSubTree'])->name('user.tree.fetchSubTree');
    Route::post('/mine', [RoiController::class, 'mineRoi'])->name('mine.roi');


    Route::get('user/bonanza', [UserBonanzaController::class, 'index'])->name('user.bonanza.index');
});
