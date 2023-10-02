<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
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
//Get api for fetch users
Route::get('/users/{id?}', [UserController::class, 'showUser']);

//Post api for add/insert users
Route::post('/add-user', [UserController::class, 'addUser']);

//Post api for add/insert multiple users
Route::post('/add-multiple-users',[UserController::class, 'addMultipleUsers']);

//Put api for update users
Route::put('/update-user/{id}', [UserController::class,'updateUser']);

//Patch api for update users's single record
Route::patch('/update-single-record/{id}', [UserController::class, 'UpdateSingleRecord']);

//Delete api for delete user
Route::delete('/delete-user/{id}', [UserController::class, 'deleteUser']);

//Delete api for delete user with json
Route::delete('/delete-user-json', [UserController::class, 'deleteUserJson']);

//Delete api for delete multiple user
Route::delete('/delete-multiple-user/{ids}', [UserController::class, 'deleteMultipleUser']);

//Delete api for delete multiple user with json
Route::delete('/delete-multiple-user-json', [UserController::class, 'deleteMultipleUserJson']);




//Laravel Passport
Route::post('user-register-passport', [UserController::class, 'userRegisterPassport']);
