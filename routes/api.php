<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\userController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('user-create', [userController::class, 'userCreate']);

Route::put('user-update/{id}', [userController::class, 'userUpdate']);
Route::delete('user-delete/{id}', [userController::class, 'userDelete']);

Route::post('login', [UserController::class, 'login']);

Route::middleware('auth:api')->group(function(){
    Route::get('user-get', [UserController::class, 'userGet']);
    Route::get('user-get-details/{id}', [userController::class, 'userGetDetails']);
    Route::get('logout', [userController::class, 'logout']);

});
