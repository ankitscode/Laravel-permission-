<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommenController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorRoleController;
use App\Http\Controllers\ManageRoleController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\TreatmentController;

use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

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
//route for user login
Route::get('/', function () {
    return redirect()->route('login');
});

//route for user signup
Route::get('/Signup', function () {
    return redirect()->route('register');
})->name('Signup');

Route::group(['middleware' => ['auth']], function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/change-language/{lang}', [DashboardController::class, 'changeLanguage'])->name('admin.changeLanguage');
    
    Route::get('/refreshdashboard', [DashboardController::class, 'refresh'])->name('refreshdashboard');

    
    Route::get('/change-password', [DashboardController::class, 'changePassword'])->name('changePassword');

    
    Route::post('/change-password/store', [DashboardController::class, 'changePasswordStore'])->name('changePasswordStore');

    Route::group(['prefix' => '/user'], function () {
    
        Route::get('userindex', [UserController::class, 'index'])->name('userindex');
        
        Route::get('userindexdatatab', [UserController::class, 'datatab'])->name('datatable.index');

        Route::post('adduser', [UserController::class, 'store'])->name('adduser');

        Route::get('useredit/{id}', [UserController::class, 'edit'])->name('edituser');

        Route::post('userupdate', [UserController::class, 'update'])->name('userupdate');

        Route::get('deleteuser/{id}', [UserController::class, 'destroy'])->name('deleteuser');

        Route::get('showuser/{id}', [UserController::class, 'show'])->name('showuser');

    });
    
    #doctor role route
    Route::group(['prefix' => 'user',], function () {
        Route::get('role', [ManageRoleController::class, 'index'])->name('admin.roleList');
        Route::get('/create', [ManageRoleController::class, 'create'])->name('admin.createRole');
        Route::post('/store', [ManageRoleController::class, 'store'])->name('admin.storeRole');
        Route::get('/show/{id}', [ManageRoleController::class, 'show'])->name('admin.showRole');
        Route::get('/edit/{id}', [ManageRoleController::class, 'edit'])->name('admin.editRole');
        Route::post('/update/{id}', [ManageRoleController::class, 'update'])->name('admin.updateRole');
        Route::post('/destroy/{id}', [ManageRoleController::class, 'destroy'])->name('admin.destroyRole');
        Route::get('/roles-list-table', [ManageRoleController::class, 'dataTableRolesListTable'])->name('dataTable.dataTableRolesListTable');
    });

    Route::group(['prefix'=>'user'],function(){
     
        Route::get('petsindex',[PetController::class,'create'])->name('petsindex');
        Route::get('datatablepetsindex',[PetController::class,'datatablepetsindex'])->name('datatable.petsindex');
        Route::post('createPet',[PetController::class,'store'])->name('createPet');
        Route::get('edit/{id}', [PetController::class, 'edit'])->name('editPet');
        Route::post('update/{id}', [PetController::class, 'update'])->name('updatePet');
        Route::get('delete/{id}', [PetController::class, 'destroy'])->name('deletePet');

    });
});


Route::group(['middleware' => 'doctor'], function () {

    Route::group(['prefix' => '/doctor'], function () {

        // Route::post('/changepassword', [DoctorController::class, 'change_Password'])->name('changePassword');

        Route::get('doctorindex', [DoctorController::class, 'index'])->name('doctorindex');

        Route::get('showdoctor/{id}', [DoctorController::class, 'show'])->name('showdoctor');

        Route::post('createdoctor', [DoctorController::class, 'store'])->name('createdoctor');

        Route::get('editdoctor/{id}', [DoctorController::class, 'edit'])->name('editdoctor');

        Route::post('updatedoctor/{id}', [DoctorController::class, 'update'])->name('updatedoctor');

        Route::get('deletedoctor/{id}', [DoctorController::class, 'destroy'])->name('deletedoctor');

        Route::get('datatabelDoctor', [DoctorController::class, 'datatable'])->name('datatabelDoctor');
    });

    Route::group(['prefix'=>'/doctor'],function(){
        Route::get('/treatmentlist', [TreatmentController::class, 'treatmentTablelist'])->name('treatmentTablelist');
        Route::get('/treatmentindex',[TreatmentController::class,'index'])->name('treatmentindex');
        Route::post('/createTreatment',[TreatmentController::class,'store'])->name('createTreatment');
        Route::get('editTreatment/{id}',[TreatmentController::class,'edit'])->name('editTreatment');
        Route::post('updateTreatment/{id}',[TreatmentController::class,'update'])->name('updateTreatment');
        Route::get('delete/{id}',[TreatmentController::class,'destroy'])->name('deleteTreatment');
    });

    Route::group(['prefix' => 'doctorrole',], function () {
        Route::get('/', [DoctorRoleController::class, 'index'])->name('doctor.roleList');
        Route::get('/create', [DoctorRoleController::class, 'create'])->name('doctor.createRole');
        Route::post('/store', [DoctorRoleController::class, 'store'])->name('doctor.storeRole');
        Route::get('/show/{id}', [DoctorRoleController::class, 'show'])->name('doctor.showRole');
        Route::get('/edit/{id}', [DoctorRoleController::class, 'edit'])->name('doctor.editRole');
        Route::post('/update/{id}', [DoctorRoleController::class, 'update'])->name('doctor.updateRole');
        Route::post('/destroy/{id}', [DoctorRoleController::class, 'destroy'])->name('doctor.destroyRole');
        Route::get('/roles-list', [DoctorRoleController::class, 'dataTableRoles'])->name('dataTable.dataTableRoles');
    });
});

// Auth::routes(['verify'=>true]);
require __DIR__ . '/auth.php';
