<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('/createRoom', [ App\Http\Controllers\RoomController::class, 'create' ]);
Route::post('/changePass', [ App\Http\Controllers\RoomController::class, 'changePass' ]);
Route::post('/updateFEN', [ App\Http\Controllers\RoomController::class, 'store' ]);
Route::post('/setWhiteName', [ App\Http\Controllers\RoomController::class, 'setWhiteName' ]);
Route::post('/setBlackName', [ App\Http\Controllers\RoomController::class, 'setBlackName' ]);
Route::get('/readFEN/{code}', [ App\Http\Controllers\RoomController::class, 'show' ]);
Route::get('/getFEN/{code}', [ App\Http\Controllers\RoomController::class, 'getEventStream' ]);
Route::post('/processMail', [ App\Http\Controllers\MailController::class, 'send' ]);
Route::post('/postChat', [ App\Http\Controllers\ChatController::class, 'post' ]);
Route::post('/getNewRoom', [
    "uses" => 'RoomController@getNewRoom',
    "as" => 'getNewRoom'
]);
Route::post('/getLatestRoom', [
    "uses" => 'RoomController@getLatestRoom',
    "as" => 'getLatestRoom'
]);
Route::post('/joinRoom', [
    "uses" => 'RoomController@join',
    "as" => 'join'
]);
Route::post('/hasRoomcode', [
    "uses" => 'RoomController@hasRoomcode',
    "as" => 'hasRoomcode'
]);
Route::post('/getRoomIds', [
    "uses" => 'RoomController@getRoomIds',
    "as" => 'getRoomIds'
]);
Route::post('/updateResult', [
    "uses" => 'RoomController@updateResult',
    "as" => 'updateResult'
]);
Route::post('/updateSideResult', [
    "uses" => 'RoomController@updateSideResult',
    "as" => 'updateSideResult'
]);
Route::get('/getPass/{code}', [
    "uses" => 'RoomController@getPass',
    "as" => 'getPass'
]);