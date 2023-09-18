<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RolesAndPermissionsController;
use App\Http\Controllers\RolesAndPermissionsRestController;

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
    if (isset($_GET['locale']) && $_GET['locale'] != null && '' != trim($_GET['locale'])
            && in_array($_GET['locale'], ['en', 'es'])) {
        App::setLocale($_GET['locale']);
    }
    return view('welcome');
});

// Login page
Route::get(
    '/login',
    [ AuthController::class, 'login' ]);

// Logout
Route::get(
    '/logout',
    [ AuthController::class, 'logout' ]);

// Login with Google
Route::get(
    '/login-google',
    [ AuthController::class, 'loginGoogle' ]);

// Successful login with Google
Route::get(
    '/login-google-callback',
    [ AuthController::class, 'handleLoginGoogle' ]);

// Login with Facebook
Route::get(
    '/login-facebook',
    [ AuthController::class, 'loginFacebook' ]);

// Successful login with Google
Route::get(
    '/login-facebook-callback',
    [ AuthController::class, 'handleLoginFacebook' ]);

// Profile page
Route::get(
    '/profile',
    [ AuthController::class, 'profile' ]);

// Profile page
Route::post(
    '/update-profile',
    [ AuthController::class, 'updateProfile' ]);

// Profile page
Route::get(
    '/roles-and-permissions',
    [ SessionController::class, 'roles' ]);

// Profile page
Route::get(
    '/role',
    [ SessionController::class, 'role' ]);

// Relate permission to role
Route::post(
    '/relate-permission-to-role',
    [ RolesAndPermissionsRestController::class, 'relatePermissionToRole' ]);

// Profile page
Route::post(
    '/update-role',
    [ RolesAndPermissionsController::class, 'updateRole' ]);
