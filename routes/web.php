<?php

use App\Http\Controllers\FriendController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BillController;
use App\Http\Controllers\ExpenseController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Redirect root URL based on authentication status
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('bills.index');
    }
    return redirect()->route('register');
})->name('home');

// Authentication routes 
require __DIR__.'/auth.php';

// Protected routes - only accessible when logged in
Route::middleware(['auth'])->group(function () {
    // Bills routes
    Route::resource('bills', BillController::class);
    Route::post('bills/{bill}/settle', [BillController::class, 'settle'])->name('bills.settle');
    
    // Expenses routes
    Route::get('bills/{bill}/expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');
    Route::post('bills/{bill}/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    
    // Friends routes
    Route::get('/friends/search', [FriendController::class, 'search'])->name('friends.search');
    Route::post('/friends/request/{user}', [FriendController::class, 'sendRequest'])->name('friends.request');
    Route::get('/friends/requests', [FriendController::class, 'requests'])->name('friends.requests');
    Route::post('/friends/accept/{request}', [FriendController::class, 'accept'])->name('friends.accept');
    Route::post('/friends/reject/{request}', [FriendController::class, 'reject'])->name('friends.reject');
});

// Guest routes - only accessible when NOT logged in
Route::middleware(['guest'])->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');
});
