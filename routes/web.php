<?php

use App\Http\Controllers\ActionsController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ViewsController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ReturnController;

Route::get('/', [ViewsController::class, 'index'])->name('index');
Route::get('/register', [ViewsController::class, 'register'])->name('register')->middleware('guest');
Route::get('/login', [ViewsController::class, 'login'])->name('login')->middleware('guest');
Route::post('/register', [ActionsController::class, 'register']);
Route::post('/login', [ActionsController::class, 'login']);
Route::get('/logout', [ActionsController::class, 'logout'])->name('logout')->middleware('auth');
Route::get('/profile', [ViewsController::class, 'profile'])->name('profile')->middleware('auth');
Route::get('/basket', [ViewsController::class, 'basket'])->name('basket')->middleware('auth');
Route::get('/thing/{thing}', [ViewsController::class, 'thing'])->name('thing.show');
Route::post('/cart/updateSize/{thing}', [CartController::class, 'updateSize'])->name('cart.updateSize');
Route::post('/cart/add/{thing}', [CartController::class, 'add'])->name('cart.add')->middleware('auth');
Route::post('/cart/decrease/{thing}', [CartController::class, 'decrease'])->name('cart.decrease')->middleware('auth');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear')->middleware('auth');
Route::delete('/cart/remove/{thing}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/increase/{thing}', [CartController::class, 'increase'])->name('cart.increase')->middleware('auth');
Route::post('/cart/apply-promo-code', [CartController::class, 'applyPromoCode'])->name('cart.applyPromoCode');
Route::post('/checkout', [CartController::class, 'checkout'])->name('cart.checkout')->middleware('auth');
Route::put('/profile', [ActionsController::class, 'profile_update'])->name('profile.update')->middleware('auth');
Route::get('/categories', [ViewsController::class, 'categories'])->name('categories');
Route::get('/categories/{category}', [ViewsController::class, 'category'])->name('category.show');
Route::get('/invoice/{receipt}', [InvoiceController::class, 'generateInvoice'])->name('invoice.generate')->middleware('auth');
Route::get('/invoice/refund/{receipt}', [InvoiceController::class, 'generateInvoiceRefund'])->name('invoice.refund.generate')->middleware('auth');
Route::get('/receipt/refund/{receipt}', [ViewsController::class, 'refund'])->name('receipt.return.request')->middleware('auth');
Route::post('/receipt/refund/{receipt}', [ReturnController::class, 'refund'])->name('receipt.return.process')->middleware('auth');
Route::post('/review/{review}/like', [ActionsController::class, 'like'])->name('review.like')->middleware('auth');
Route::post('/thing/{thing}', [ActionsController::class, 'create_review'])->name('thing.review')->middleware('auth');
Route::post('/wishlists/{thing}', [WishlistController::class, 'store'])->name('wishlists.add')->middleware('auth');
Route::delete('/wishlists/{thing}', [WishlistController::class, 'destroy'])->name('wishlists.remove')->middleware('auth');
Route::get('/wishlist', [ViewsController::class, 'wishlist'])->name('wishlist')->middleware('auth');
