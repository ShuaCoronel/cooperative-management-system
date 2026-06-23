<?php

use App\Enums\UserRole;
use App\Http\Controllers\Admin\LoanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\SavingsTransactionController;
use App\Http\Controllers\Admin\ShareCapitalTransactionController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


// landing route get without login session
Route::get('/', function () {
    return view('welcome');
});

// with login session
Route::get('/dashboard', function () {
    // initialize user variable using Auth class user() function
    $user = Auth::user();

    // validate user data role and return respective dashboard for its role
    if ($user->role === UserRole::ADMIN) {
        return view('admin.dashboard', ['user'=>$user]);

    }

    // if not go to member.dashboard
    return view('member.dashboard', [
    // 'user' pair to $user variable and 'member' check the $user variable member data
    'user' => $user,
    'member' => $user->member
    ]);
})->middleware(['auth', 'verified'])->name('dashboard'); //middleware calls the auth and verify middleware func, name() function for dashboard nickname




Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



// admin only Financial operation
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function() {

    //Share capital transactions  check controller/ShareCapitalTransaction
    Route::post('/members/{member:member_id_number}/share-capital', [ShareCapitalTransactionController::class, 'store'])
    ->name('share-capital.store');


    // Savings account transactions
    Route::post('/savings-accounts/{savingsAccount:account_number}/transactions',[SavingsTransactionController::class,'store'])
    ->name('savings.store');

    // Loan Transactions
    Route::post('/members/{member_id_number}/loans', [LoanController::class, 'store'])
    ->name('loans.store');



    }
);







require __DIR__.'/auth.php';
