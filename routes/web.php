<?php

use App\Enums\UserRole;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


// landing route get withou login session
Route::get('/', function () {
    return view('welcome');
});


// with login session
Route::get('/dashboard', function () {

    // initialize user variable using Auth class user() function
    $user = Auth::user();

    // validat user data role and return respective dashboard for its role
    if ($user -> role === UserRole::ADMIN) {
    return view('admin.dashboard', ['user'=>$user]);

    }

    // else statement if the if block not satisfied, it goes to other condition
    return view('member.dashboard', [
    
    // 'user' pair to $user variable and 'member' check teh $user variable member data
    'user' => $user,
    'member' => $user->member
    
    ]);


    
})->middleware(['auth', 'verified'])->name('dashboard'); //middleware calls the auth and verify middleware func, name() function for dashboard nickname

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
