<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Auth;

/*
use App\Http\Controllers\Admin\RestaurantController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\TermController;
*/

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Middleware\Subscribed;
use App\Http\Middleware\NotSubscribed;

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

require __DIR__.'/auth.php';

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'auth:admin'], function () {
    Route::get('home', [Admin\HomeController::class, 'index'])->name('home');

    Route::resource('users', Admin\UserController::class)->only(['index', 'show']);

    Route::resource('restaurants', Admin\RestaurantController::class)->only(['index', 'show', 'create', 'store', 'edit', 'update', 'destroy']);

    Route::resource('categories', Admin\CategoryController::class)->only(['index', 'store','update','destroy']);

    Route::resource('company', Admin\CompanyController::class)->only(['index', 'edit', 'update']);

    Route::resource('terms', Admin\TermController::class)->only(['index', 'edit', 'update']);
});

Route::group(['middleware' => 'guest:admin'], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::resource('restaurants', HomeController::class)->only(['index','show']);
    Route::get('company', [CompanyController::class, 'index'])->name('company.index');
    Route::get('terms', [TermController::class, 'index'])->name('terms.index');
    Route::resource('restaurants', RestaurantController::class)->only(['index', 'show']);

});

    Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::resource('user', UserController::class)->only(['index', 'edit', 'update']);
    
    Route::group(['middleware' => NotSubscribed::class], function () {
        Route::group(['prefix' => '/subscription', 'as' => 'subscription.'], function () {
            Route::get('/create', [SubscriptionController::class, 'create'])->name('create');
            Route::post('/', [SubscriptionController::class, 'store'])->name('store');
        });
});

Route::group(['middleware' => Subscribed::class], function () {

    Route::resource('restaurants.reviews', ReviewController::class)
        ->only(['create', 'store', 'edit', 'update', 'destroy'])
        ->parameters(['reviews' => 'review']);

    Route::group(['prefix' => '/subscription', 'as' => 'subscription.'], function () {
        Route::get('/edit', [SubscriptionController::class, 'edit'])->name('edit');
        Route::match(['put', 'patch'], '/', [SubscriptionController::class, 'update'])->name('update');
        Route::get('/cancel', [SubscriptionController::class, 'cancel'])->name('cancel');
        Route::delete('/', [SubscriptionController::class, 'destroy'])->name('destroy');
    });
});
});