<?php

use App\Enums\UserRole;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\LoanController;
use App\Http\Controllers\Admin\LoanPaymentController;
use App\Http\Controllers\Admin\SavingsController;
use App\Http\Controllers\Admin\SavingsTransactionController;
use App\Http\Controllers\Admin\ShareCapitalTransactionController;
use App\Http\Controllers\member\DashboardController;
use App\Http\Controllers\member\ShowSavingsController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


// landing route get without login session
Route::get('/', function () {
    return view('hero');
});






// with login session
Route::get('/dashboard', function () {

    // initialize user variable but grabing session Auth::user() inject in $user
    $user = Auth::user();

    // validate user data role and return respective dashboard for its role
    if ($user->role === UserRole::ADMIN) {
        return redirect()->route('admin.dashboard');
    }
    // if not go to member.dashboard
    return redirect()->route('member.dashboard');
    
    })->middleware(['auth','verified'])->name('dashboard');
    //middleware calls the auth and verify middleware func, name() function for dashboard nickname







Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});








// Grouped MEMBER route wrapped in member middleware, added a customized 'member' middleware in bootstrap
Route::middleware(['auth','member'])->prefix('member')->name('member.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    
    Route::get('/savings/{accountNumber}', [ShowSavingsController::class, 'show'])->name('savings.show');

});








//GROUP ADMIN
// >>> [FIXED] Added 'admin' middleware to protect admin-only financial routes from unauthorized access
// check app/bootstrap for admin middleware alias
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function() {

    //Dashboard
    Route::get('/dashboard', [AdminDashboardController::class,'index'])->name('dashboard');


    //Share Capital
    //Share capital transactions  check controller/ShareCapitalTransaction
    Route::post('/members/{member:member_id_number}/share-capital', [ShareCapitalTransactionController::class, 'store'])
    ->name('share-capital.store');




    // SAVINGS
    // Savings account transactions
    Route::post('/savings-accounts/{savingsAccount:account_number}/transactions',[SavingsTransactionController::class,'store'])
    ->name('savings.transactions.store');
    Route::get('/savings/{savingsAccount}', [SavingsController::class, 'show'])->name('savings.show');





    // Loan
    // >>> [FIXED] Changed {member_id_number} to {member:member_id_number} for proper route model binding
    Route::post('/members/{member:member_id_number}/loans', [LoanController::class, 'store'])
    ->name('loans.store');


    //Loan Payment Engine Routes
    Route::get('/loan-payments', [LoanPaymentController::class, 'index'])->name('loan-payments.index');
    Route::get('/loan-payments/create', [LoanPaymentController::class, 'create'])->name('loan-payments.create');
    Route::post('/loan-payments/store', [LoanPaymentController::class, 'store'])->name('loan-payments.store');


    }
);







require __DIR__.'/auth.php';
