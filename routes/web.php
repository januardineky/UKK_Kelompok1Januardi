<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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
    return view('login');
});
Route::post('/auth', [UserController::class, 'auth']);
Route::middleware(['status'])->group(function () {

    Route::get('/home', [UserController::class, 'home']);

    Route::post('/home/createmajor', [UserController::class, 'createmajors']);
    Route::get('/home/inputmajor', [UserController::class, 'inputmajors']);
    Route::get('/home/editmajor/{id}', [UserController::class, 'editmajors']);
    Route::post('/home/updatemajor/{id}', [UserController::class, 'updatemajors']);
    Route::get('/home/deletemajor/{id}', [UserController::class, 'deletemajors']);

    Route::get('/home/students', [UserController::class, 'students']);
    Route::get('/home/students/search', [UserController::class, 'search']);

    Route::get('/home/inputstudent', [UserController::class, 'inputstudents']);
    Route::post('/home/createstudent', [UserController::class, 'createstudents']);
    Route::get('/home/students/edit/{id}', [UserController::class, 'editstudent']);
    Route::post('/home/students/update/{id}', [UserController::class, 'updatestudent']);
    Route::get('/home/students/delete/{id}', [UserController::class, 'deletestudent']);

    Route::get('/home/inputassessor', [UserController::class, 'inputassessor']);
    Route::post('/home/inputassessor', [UserController::class, 'createassessor']);
    Route::get('/home/editassessor/{id}', [UserController::class, 'editassessor']);
    Route::post('/home/updateassessor/{id}', [UserController::class, 'updatesassessor']);
    Route::get('/home/deleteassessor/{id}', [UserController::class, 'deleteassessor']);

    Route::get('/home/profile', [UserController::class, 'profile']);
    Route::get('/home/editadmin/{id}', [UserController::class, 'editprofile']);
    Route::post('/home/updateadmin/{id}', [UserController::class, 'updateadmin']);

    Route::get('/home/table/exam/{id}', [UserController::class, 'exam']);
    Route::get('/home/table', [UserController::class, 'table']);

    Route::get('/home/logout', [UserController::class, 'logout']);

});