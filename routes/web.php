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

    Route::get('/index', [UserController::class, 'index']);

    Route::get('/index/inputcompetency', [UserController::class, 'inputcompetency']);
    Route::post('/index/inputcompetency', [UserController::class, 'createcompetency']);
    Route::get('/index/edit/{id}', [UserController::class, 'editcompetency']);
    Route::post('/index/update/{id}', [UserController::class, 'updatecompetency']);
    Route::get('/index/delete/{id}', [UserController::class, 'deletestandard']);

    Route::get('/index/detail/{id}', [UserController::class, 'showelement']);
    Route::get('/index/inputelement', [UserController::class, 'inputelement']);
    Route::post('/index/inputelement', [UserController::class, 'createelement']);
    Route::get('/index/detail/edit/{id}', [UserController::class, 'editelement']);
    Route::post('/index/detail/edit/{id}', [UserController::class, 'updateelement']);
    Route::get('/index/detail/delete/{id}', [UserController::class, 'deleteelement']);

    Route::get('/home/inputadmin', [UserController::class, 'createadmin']);
    Route::post('/home/inputadmin', [UserController::class, 'inputadmin']);
    Route::get('/home/deleteadmin/{id}', [UserController::class, 'deleteadmin']);

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

    Route::get('/index/profile', [UserController::class, 'profil']);

    Route::get('/home/profile', [UserController::class, 'profile']);
    Route::get('/home/editadmin/{id}', [UserController::class, 'editprofile']);
    Route::post('/home/updateadmin/{id}', [UserController::class, 'updateadmin']);

    Route::get('/home/table/exam/{id}', [UserController::class, 'exam']);
    Route::get('/home/table', [UserController::class, 'table']);

    Route::get('/home/logout', [UserController::class, 'logout']);

});
