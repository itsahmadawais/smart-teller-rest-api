<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\STAuthController;
use App\Http\Controllers\STSettingsController;
use App\Http\Controllers\STUtilsController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * Login via face ID or USER ID
 */
Route::get('login-via-faceID/{id}',[STAuthController::class,'loginViaFaceID']);

Route::get('settings/{user_id}/{token}',[STSettingsController::class,'get_settings']);

Route::get('timeline/{user_id}/{token}',[STUtilsController::class,'get_timeline_data']);
Route::get('timetable/{user_id}/{token}',[STUtilsController::class,'get_time_table']);
Route::get('attendance/{user_id}/{token}',[STUtilsController::class,'get_attendence_data']);
Route::get('todo/{user_id}/{token}',[STUtilsController::class,'get_todo_data']);

Route::get('logout/{id}/{token}',[STAuthController::class,'logout']);
/**
 * Login via email and password
 */
Route::post('login',[STAuthController::class,'login']);
Route::post('update_settings',[STSettingsController::class,'set_settings']);

Route::get('get_profile/{id}/{token}',[STAuthController::class,'get_profile']);
Route::post('update_password',[STAuthController::class,'update_password']);
Route::post('update_profile',[STAuthController::class,'update_profile']);
Route::post('update_profile_picture',[STAuthController::class,'update_profile_picture']);
