<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Province;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\ServiceController;
use App\Http\Controllers\API\ProductController;

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

Route::get('/cities/{province_id}', function($province_id){
    $province = Province::findOrFail($province_id);

    return response()->json($province->cities);
});

Route::controller(RegisterController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
});
        
// Route::middleware('auth:sanctum')->group( function () {
    Route::resource('services', ServiceController::class);
    Route::resource('products', ProductController::class);

    Route::get('/search', [App\Http\Controllers\API\LandingController::class, 'search']);
// });

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
