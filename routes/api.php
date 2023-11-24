<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// user types: admin and user
// roles : 1 admin, 2 user

// auth routes, register is for reqular users, login for both
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'admin'])->group(function () {

   // CRUD for admin
    Route::prefix('admin')->group(function () {
        Route::post('/', [AdminController::class, 'createAdmin']);
        Route::get('/', [AdminController::class, 'getAdminsList']);
        Route::get('/{id}', [AdminController::class, 'getAdmin']);
        Route::patch('/{id}', [AdminController::class, 'updateAdminInfo']);
        Route::delete('/{id}', [AdminController::class, 'deleteAdmin']);
    });

    // CRUD for users, only the ones that are 'logically' required
    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'getUsersList']);
        Route::get('/{id}', [UserController::class, 'getUser']);
        Route::delete('/{id}', [UserController::class, 'deleteUser']);
    });

    // CRUD for products
    Route::prefix('product')->group(function () {
        Route::post('/', [ProductController::class, 'createProduct']);
        Route::get('/', [ProductController::class, 'getProductsList']);
        Route::get('/{id}', [ProductController::class, 'getProduct']);
        Route::patch('/{id}', [ProductController::class, 'updateProductInfo']);
        Route::delete('/{id}', [ProductController::class, 'deleteProdcut']);
    });

});

// **** Business Logic **** //