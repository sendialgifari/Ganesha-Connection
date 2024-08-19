<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ServiceCategoryController;
use App\Http\Controllers\ServiceCommentController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductCommentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\StaticPageController;
use App\Http\Controllers\WorkUnitController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['splade'])->group(function () {
    // Registers routes to support the interactive components...
    Route::spladeWithVueBridge();

    // Registers routes to support password confirmation in Form and Link components...
    Route::spladePasswordConfirmation();

    // Registers routes to support Table Bulk Actions and Exports...
    Route::spladeTable();

    // Registers routes to support async File Uploads with Filepond...
    Route::spladeUploads();

    // Route::get('/', function () {
    //     return view('welcome', [
    //         'canLogin' => Route::has('login'),
    //         'canRegister' => Route::has('register'),
    //         'laravelVersion' => Application::VERSION,
    //         'phpVersion' => PHP_VERSION,
    //     ]);
    // });

    Route::middleware([
        'auth:sanctum',
        config('jetstream.auth_session'),
        'verified',
    ])->group(function () {
        Route::view('/home', 'dashboard')->name('dashboard');
        Route::group(['prefix' => 'dashboard'], function () {

            Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
            Route::get('/partner_registration', [UserController::class, 'partner_registration'])->name('partner_registration');
            
            Route::middleware(['role:superadmin'])->group(function () {
                Route::resource('admin_categories', AdminCategoryController::class);
            });

            Route::middleware(['role:admin|superadmin'])->group(function () {
                Route::resource('users', UserController::class);
                Route::resource('service_categories', ServiceCategoryController::class);
                Route::resource('product_categories', ProductCategoryController::class);
                Route::resource('work_units', WorkUnitController::class);
                Route::resource('sliders', SliderController::class);
                Route::resource('static_pages', StaticPageController::class);
                Route::resource('product_comments', ProductCommentController::class);
                Route::resource('service_comments', ServiceCommentController::class);
                Route::get('visitors', [VisitorController::class, 'index'])->name('visitors');
                Route::resource('roles', RoleController::class);
                Route::resource('permissions', PermissionController::class);
                Route::get('partner_approval', [UserController::class, 'partner_approval'])->name('partner_approval');
                Route::put('partner_approval_update', [UserController::class, 'partner_approval_update'])->name('partner_approval_update');
                Route::put('partner_decline_update', [UserController::class, 'partner_decline_update'])->name('partner_decline_update');
            });

            Route::middleware(['role:admin|partner|superadmin'])->group(function () {
                Route::resource('services', ServiceController::class);
                Route::resource('products', ProductController::class);
            });

            Route::middleware(['role:user'])->group(function () {
                Route::put('/partner_registration_update', [UserController::class, 'partner_registration_update'])->name('partner_registration_update');
            });


        });
    });

    Route::controller(GoogleController::class)->group(function () {
        Route::get('oauth/google', 'redirectToGoogle')->name('auth.google');
        Route::get('oauth/google/callback', 'handleGoogleCallback');
    });

    Route::get('/', [LandingController::class, 'index'])->name('index');
    Route::get('/search', [LandingController::class, 'search'])->name('search');
    Route::get('/static/{slug}', [LandingController::class, 'static_page'])->name('static');
    Route::get('/{type}/{slug}', [LandingController::class, 'detail'])->name('detail');
    Route::resource('product_comments', ProductCommentController::class);
    Route::resource('service_comments', ServiceCommentController::class);
});