<?php

use App\Models\Shop;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDFGeneratorController;
use App\Http\Controllers\Filament\LogoutController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ShopsController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        $shops = Shop::all();
        return view('dashboard', compact('shops'));
    })->name('dashboard');
});
Route::post('/auth/logout', [LogoutController::class, 'logout'])->name('filament.admin.auth.logout');

Route::get('pdf/{record}', PDFGeneratorController::class)->name('pdf');
Route::get('/select-shop/{shopId}', [ShopController::class, 'slectShop'])->name('select.shop');

// Route::get('logagain', function () {
//     return redirect('/login');
// })->name('login');