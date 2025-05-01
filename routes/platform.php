<?php

declare(strict_types=1);

use App\Orchid\Screens\Things\Order\OrdersEditScreen;
use App\Orchid\Screens\Things\Order\OrdersListScreen;
use App\Orchid\Screens\Things\Returns\ReturnsListScreen;
use App\Orchid\Screens\Things\Review\ReviewsListScreen;
use App\Orchid\Screens\Things\Thing\ThingsEditScreen;
use App\Orchid\Screens\Things\Thing\ThingsListScreen;
use App\Orchid\Screens\UsersListScreen;
use Illuminate\Support\Facades\Route;
use App\Orchid\Screens\Things\Category\CategoriesListScreen;
use App\Orchid\Screens\Things\Category\CategoriesEditScreen;
use App\Orchid\Screens\ProfileScreen;
use App\Orchid\Screens\Things\Returns\ReturnsEditScreen;
use App\Orchid\Screens\Things\SizeAndCount\SizeAndCountsListScreen;
use App\Orchid\Screens\Things\PromoCode\PromoCodesListScreen;
use App\Orchid\Screens\Things\PromoCode\PromoCodesEditScreen;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the need "dashboard" middleware group. Now create something great!
|
*/

// Main
Route::screen('/main', CategoriesListScreen::class)->name('platform.main');
Route::screen('/categories', CategoriesListScreen::class)->name('platform.category.list');
Route::screen('/category/{category?}', CategoriesEditScreen::class)->name('platform.category.edit');
Route::screen('/things', ThingsListScreen::class)->name('platform.thing.list');
Route::screen('/thing/{thing?}', ThingsEditScreen::class)->name('platform.thing.edit');
Route::screen('/orders', OrdersListScreen::class)->name('platform.order.list');
Route::screen('/order/{receipt?}', OrdersEditScreen::class)->name('platform.order.edit');
Route::screen('/returns', ReturnsListScreen::class)->name('platform.return.list');
Route::screen('/return/{return?}', ReturnsEditScreen::class)->name('platform.return.edit');
Route::screen('/reviews', ReviewsListScreen::class)->name('platform.review.list');
Route::screen('/users', UsersListScreen::class)->name('platform.user.list');
Route::screen('/returns', ReturnsListScreen::class)->name('platform.return.list');
Route::screen('/sizeAndCounts', SizeAndCountsListScreen::class)->name('platform.sizeAndCount.list');
Route::screen('/profile', ProfileScreen::class)->name('platform.profile');
Route::screen('promocodes', PromoCodesListScreen::class)
    ->name('platform.promocode.list');

Route::screen('promocode/{promoCode?}', PromoCodesEditScreen::class)
    ->name('platform.promocode.edit');

