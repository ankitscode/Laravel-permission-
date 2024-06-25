<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use  App\Http\Controllers\Api\DoctorAuthController;


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

Route::post('/login', [AuthController::class, 'login'])->name('loginapi');
Route::post('/register', [AuthController::class, 'register'])->name('registerapi');

// login and register Api for doctor
Route::post('/doctorregister', [DoctorAuthController::class, 'doctorRegister'])->name('doctorregisterapi');
Route::post('/doctorlogin', [DoctorAuthController::class, 'doctorLogin'])->name('doctorloginapi');

//Api for User model using api & default guard
Route::group(['middleware' => ['auth:api']], function () {
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logoutapi');
    Route::get('/profile', [AuthController::class, 'profile'])->name('profileapi');
    Route::post('/profileupdate', [AuthController::class, 'profileUpdate'])->name('profileupdateapi');
    Route::post('/profiledelete', [AuthController::class, 'profileDelete'])->name('profiledeleteapi');
    Route::post('/profilecreate', [AuthController::class, 'profileCreate'])->name('profileCreateapi');
    Route::post('/image', [AuthController::class, 'saveImageBase64'])->name('imageapi');
});
    
//Api for doctor model which using doctor guard
Route::group(['middleware' => ['auth:doctor_api']], function () {
    Route::post('/doctorprofiledelete', [DoctorAuthController::class, 'doctorProfileDelete'])->name('logoutapi');
    Route::get('/doctorprofile', [DoctorAuthController::class, 'getDoctorProfile'])->name('doctorprofileapi');
    Route::post('/doctorprofileupdate', [DoctorAuthController::class, 'doctorProfileUpdate'])->name('doctorprofileupdateapi');
    Route::post('/doctorprofilelogout', [DoctorAuthController::class, 'doctorProfileLogout'])->name('doctorprofileLogoutapi');
    Route::post('/doctorprofilecreate', [DoctorAuthController::class, 'doctorProfileCreate'])->name('doctorprofileCreateapi');   
});