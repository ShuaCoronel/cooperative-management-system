<?php

use App\Enums\UserRole;
use App\Http\Controllers\Admin\LoanController;
use App\Http\Controllers\Admin\LoanPaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\SavingsTransactionController;
use App\Http\Controllers\Admin\ShareCapitalTransactionController;
use App\Http\Controllers\member\DashboardController;
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
        return view('admin.dashboard', ['user'=>$user]);
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








// Grouped member route wrapped in member middleware, added a customized 'member' middleware in bootstrap
Route::middleware(['auth','member'])->prefix('member')->name('member.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    // testing new route manually need to delete
    Route::get('/savings/show', [DashboardController::class, 'show'])->name('savings.show');

});






// >>> [FIXED] Added 'admin' middleware to protect admin-only financial routes from unauthorized access
// check app/bootstrap for admin middleware alias
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function() {


    //Share capital transactions  check controller/ShareCapitalTransaction
    Route::post('/members/{member:member_id_number}/share-capital', [ShareCapitalTransactionController::class, 'store'])
    ->name('share-capital.store');


    // Savings account transactions
    Route::post('/savings-accounts/{savingsAccount:account_number}/transactions',[SavingsTransactionController::class,'store'])
    ->name('savings.store');

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
