<?php
use App\Http\Controllers\FileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'api','prefix' => 'auth'], function ($router) {
    Route::get('users', [UserController::class, 'index']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::put('/update/{id}', [AuthController::class, 'update']); 
    Route::delete('/delete/{id}', [AuthController::class, 'destroy']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);    
});

Route::get('users', [UserController::class, 'index']);
Route::get('users/{id}', [UserController::class, 'show']); 
Route::post('addnew', [UserController::class, 'store']); 
Route::put('usersupdate/{id}', [UserController::class, 'update']); 
Route::delete('usersdelete/{id}', [UserController::class, 'destroy']);



Route::post('/files', [FileController::class, 'fileUpload']);
Route::put('/files/{id}', [FileController::class, 'update']);
Route::delete('/filesdelete/{id}', [FileController::class, 'delete']);
Route::get('/filesget/{id}', [FileController::class, 'show']);