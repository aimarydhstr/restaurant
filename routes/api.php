<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\TagController;
use App\Http\Controllers\API\TableController;
use App\Http\Controllers\API\OrderTableController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\CouponController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function() {

	Route::prefix('auth')->group(function() {
		Route::post('/register', [AuthController::class, 'register']);
		Route::post('/login', [AuthController::class, 'login']);
		Route::post('/changepassword', [AuthController::class, 'changePassword'])->middleware('auth:sanctum');
		Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
	});

	Route::group(['middleware' => ['auth:sanctum']], function() {

		// User
		Route::prefix('user')->group(function() {
			Route::get('/', [UserController::class, 'index']);
			Route::post('/store', [UserController::class, 'store']);
			Route::get('/{id}', [UserController::class, 'show']);
			Route::put('/update/{id}', [UserController::class, 'update']);
			Route::delete('/delete/{id}', [UserController::class, 'destroy']);
		});

		// Get Login
		Route::get('/users', function(Request $request) {
			return auth()->user();
		});

		// Customer
		Route::prefix('customer')->group(function() {
			Route::get('/', [CustomerController::class, 'index']);
			Route::get('/search', [CustomerController::class, 'search']);
			Route::post('/store', [CustomerController::class, 'store']);
			Route::get('/{id}', [CustomerController::class, 'show']);
			Route::put('/update/{id}', [CustomerController::class, 'update']);
			Route::delete('/delete/{id}', [CustomerController::class, 'destroy']);
		});

		// Product
		Route::prefix('product')->group(function() {
			Route::get('/', [ProductController::class, 'index']);
			Route::get('/search', [ProductController::class, 'search']);
			Route::post('/store', [ProductController::class, 'store']);
			Route::get('/{slug}', [ProductController::class, 'show']);
			Route::put('/update/{id}', [ProductController::class, 'update']);
			Route::delete('/delete/{id}', [ProductController::class, 'destroy']);
		});

		// Category
		Route::prefix('category')->group(function() {
			Route::get('/', [CategoryController::class, 'index']);
			Route::post('/store', [CategoryController::class, 'store']);
			Route::get('/{slug}', [CategoryController::class, 'show']);
			Route::put('/update/{id}', [CategoryController::class, 'update']);
			Route::delete('/delete/{id}', [CategoryController::class, 'destroy']);
		});

		// Tag
		Route::prefix('tag')->group(function() {
			Route::get('/', [TagController::class, 'index']);
			Route::post('/store', [TagController::class, 'store']);
			Route::get('/{slug}', [TagController::class, 'show']);
			Route::put('/update/{id}', [TagController::class, 'update']);
			Route::delete('/delete/{id}', [TagController::class, 'destroy']);
		});

		// Table
		Route::prefix('table')->group(function() {
			Route::get('/', [TableController::class, 'index']);
			Route::post('/store', [TableController::class, 'store']);
			Route::post('/guest', [TableController::class, 'guest']);
			Route::put('/update/{id}', [TableController::class, 'update']);
			Route::delete('/delete/{id}', [TableController::class, 'destroy']);
		});

		// Coupon
		Route::prefix('coupon')->group(function() {
			Route::get('/', [CouponController::class, 'index']);
			Route::post('/store', [CouponController::class, 'store']);
			Route::put('/update/{id}', [CouponController::class, 'update']);
			Route::delete('/delete/{id}', [CouponController::class, 'destroy']);
		});


		Route::prefix('order')->group(function() {
			
			// OrderTable
			Route::prefix('table')->group(function() {
				Route::get('/', [OrderTableController::class, 'index']);
				Route::post('/store', [OrderTableController::class, 'store']);
				Route::put('/update/{id}', [OrderTableController::class, 'update']);
				Route::delete('/delete/{id}', [OrderTableController::class, 'destroy']);
			});

			// OrderItem
			Route::prefix('item')->group(function() {
				Route::get('/', [OrderController::class, 'index']);
				Route::post('/store', [OrderController::class, 'store']);
				Route::put('/coupon/{id}', [OrderController::class, 'coupon']);
				Route::put('/note/{id}', [OrderController::class, 'note']);
				// Update for OrderItem not for Order
				Route::put('/update/{id}', [OrderController::class, 'update']);
				Route::delete('/delete/{id}', [OrderController::class, 'destroy']);
			});

		});

		// Transaction
		Route::prefix('transaction')->group(function() {
			Route::get('/', [TransactionController::class, 'index']);
			Route::post('/store', [TransactionController::class, 'store']);
			// Route::put('/update/{id}', [TransactionController::class, 'update']);
			Route::delete('/delete/{id}', [TransactionController::class, 'destroy']);
		});

	});
});
