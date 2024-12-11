<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
use App\Models\Room;
use App\Models\User;
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

Route::get('/play-alone', function () {
  return view('human', ['headTitle' => 'Play alone', 'bodyClass' => 'home', 'roomCode' => '', 'randomRoom' => Room::where('pass', null)->where('host_id', null)->where('result', '=', null)->where('fen', '!=', 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1')->where('fen', 'LIKE', '% b %')->inRandomOrder()->first()]);
});
Route::get('/set-up', function () {
  return view('setup', ['headTitle' => 'Set up the puzzle', 'bodyClass' => 'setup', 'board' => '', 'roomCode' => '', 'randomRoom' => Room::where('pass', null)->where('host_id', null)->where('result', '=', null)->where('fen', '!=', 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1')->where('fen', 'LIKE', '% b %')->inRandomOrder()->first()]);
});
Route::get('/set-up/{board}', function ($board) {
  return view('setup', ['headTitle' => 'Set up the puzzle', 'bodyClass' => 'setup', 'board' => $board, 'roomCode' => '', 'randomRoom' => Room::where('pass', null)->where('host_id', null)->where('result', '=', null)->where('fen', '!=', 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1')->where('fen', 'LIKE', '% b %')->inRandomOrder()->first()]);
})->where(['board' => "[a-zA-Z0-9\-\/\s|&nbsp;]+"]);
Route::get('/puzzle', function () {
  return view('puzzle', ['headTitle' => 'Set up the puzzle', 'bodyClass' => 'setup', 'board' => '', 'roomCode' => '', 'randomRoom' => Room::where('pass', null)->where('host_id', null)->where('result', '=', null)->where('fen', '!=', 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1')->where('fen', 'LIKE', '% b %')->inRandomOrder()->first()]);
});
Route::get('/puzzle/{board}', function ($board) {
  return view('puzzle', ['headTitle' => 'Set up the puzzle', 'bodyClass' => 'setup', 'board' => $board, 'roomCode' => '', 'randomRoom' => Room::where('pass', null)->where('host_id', null)->where('result', '=', null)->where('fen', '!=', 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1')->where('fen', 'LIKE', '% b %')->inRandomOrder()->first()]);
})->where(['board' => "[a-zA-Z0-9\-\/\s|&nbsp;]+"]);
Route::get('/board/{fen}', function ($fen) {
  return view('board', ['headTitle' => 'Board', 'bodyClass' => 'home', 'fen' => $fen, 'roomCode' => '', 'randomRoom' => Room::where('pass', null)->where('host_id', null)->where('result', '=', null)->where('fen', '!=', 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1')->where('fen', 'LIKE', '% b %')->inRandomOrder()->first()]);
})->where(['fen' => "[a-zA-Z0-9\-\/\s|&nbsp;]+"]);

Route::get('/easiest-board/{fen}', function ($fen) {
    return view('boardAi', ['headTitle' => 'Easiest board', 'bodyClass' => 'home', 'fen' => $fen, 'roomCode' => '', 'level' => '1', 'levelTxt' => 'Easiest', 'randomRoom' => Room::where('pass', null)->where('host_id', null)->where('result', '=', null)->where('fen', '!=', 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1')->where('fen', 'LIKE', '% b %')->inRandomOrder()->first()]);
})->where(['fen' => "[a-zA-Z0-9\-\/\s|&nbsp;]+"]);
Route::get('/newbie-board/{fen}', function ($fen) {
    return view('boardAi', ['headTitle' => 'Newbie board', 'bodyClass' => 'home', 'fen' => $fen, 'roomCode' => '', 'level' => '1', 'levelTxt' => 'Newbie', 'randomRoom' => Room::where('pass', null)->where('host_id', null)->where('result', '=', null)->where('fen', '!=', 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1')->where('fen', 'LIKE', '% b %')->inRandomOrder()->first()]);
})->where(['fen' => "[a-zA-Z0-9\-\/\s|&nbsp;]+"]);
Route::get('/easy-board/{fen}', function ($fen) {
    return view('boardAi', ['headTitle' => 'Easy board', 'bodyClass' => 'home', 'fen' => $fen, 'roomCode' => '', 'level' => '2', 'levelTxt' => 'Easy', 'randomRoom' => Room::where('pass', null)->where('host_id', null)->where('result', '=', null)->where('fen', '!=', 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1')->where('fen', 'LIKE', '% b %')->inRandomOrder()->first()]);
})->where(['fen' => "[a-zA-Z0-9\-\/\s|&nbsp;]+"]);
Route::get('/normal-board/{fen}', function ($fen) {
    return view('boardAi', ['headTitle' => 'Normal board', 'bodyClass' => 'home', 'fen' => $fen, 'roomCode' => '', 'level' => '3', 'levelTxt' => 'Normal', 'randomRoom' => Room::where('pass', null)->where('host_id', null)->where('result', '=', null)->where('fen', '!=', 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1')->where('fen', 'LIKE', '% b %')->inRandomOrder()->first()]);
})->where(['fen' => "[a-zA-Z0-9\-\/\s|&nbsp;]+"]);
Route::get('/hard-board/{fen}', function ($fen) {
    return view('boardAi', ['headTitle' => 'Hard board', 'bodyClass' => 'home', 'fen' => $fen, 'roomCode' => '', 'level' => '4', 'levelTxt' => 'Hard', 'randomRoom' => Room::where('pass', null)->where('host_id', null)->where('result', '=', null)->where('fen', '!=', 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1')->where('fen', 'LIKE', '% b %')->inRandomOrder()->first()]);
})->where(['fen' => "[a-zA-Z0-9\-\/\s|&nbsp;]+"]);
Route::get('/hardest-board/{fen}', function ($fen) {
    return view('boardAi', ['headTitle' => 'Hardest board', 'bodyClass' => 'home', 'fen' => $fen, 'roomCode' => '', 'level' => '4', 'levelTxt' => 'Hardest', 'randomRoom' => Room::where('pass', null)->where('host_id', null)->where('result', '=', null)->where('fen', '!=', 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1')->where('fen', 'LIKE', '% b %')->inRandomOrder()->first()]);
})->where(['fen' => "[a-zA-Z0-9\-\/\s|&nbsp;]+"]);
Route::get('/solve-puzzle/{fen}', function ($fen) {
    return view('puzzleAi', ['headTitle' => 'Solve puzzle', 'bodyClass' => 'home', 'fen' => $fen, 'roomCode' => '', 'level' => '5', 'levelTxt' => 'Hardest', 'randomRoom' => Room::where('pass', null)->where('host_id', null)->where('result', '=', null)->where('fen', '!=', 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1')->where('fen', 'LIKE', '% b %')->inRandomOrder()->first()]);
})->where(['fen' => "[a-zA-Z0-9\-\/\s|&nbsp;]+"]);

Route::get('/', function () {
    return view('ai', ['headTitle' => 'Home', 'bodyClass' => 'home', 'roomCode' => '', 'level' => '3', 'levelTxt' => 'Normal', 'randomRoom' => Room::where('pass', null)->where('host_id', null)->where('result', '=', null)->where('fen', '!=', 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1')->where('fen', 'LIKE', '% b %')->inRandomOrder()->first()]);
});
Route::get('/easiest', function () {
    return view('ai', ['headTitle' => 'Easiest', 'bodyClass' => 'home', 'roomCode' => '', 'level' => '1', 'levelTxt' => 'Easiest', 'randomRoom' => Room::where('pass', null)->where('host_id', null)->where('result', '=', null)->where('fen', '!=', 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1')->where('fen', 'LIKE', '% b %')->inRandomOrder()->first()]);
});
Route::get('/newbie', function () {
    return view('ai', ['headTitle' => 'Newbie', 'bodyClass' => 'home', 'roomCode' => '', 'level' => '1', 'levelTxt' => 'Newbie', 'randomRoom' => Room::where('pass', null)->where('host_id', null)->where('result', '=', null)->where('fen', '!=', 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1')->where('fen', 'LIKE', '% b %')->inRandomOrder()->first()]);
});
Route::get('/easy', function () {
    return view('ai', ['headTitle' => 'Easy', 'bodyClass' => 'home', 'roomCode' => '', 'level' => '2', 'levelTxt' => 'Easy', 'randomRoom' => Room::where('pass', null)->where('host_id', null)->where('result', '=', null)->where('fen', '!=', 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1')->where('fen', 'LIKE', '% b %')->inRandomOrder()->first()]);
});
Route::get('/normal', function () {
    return view('ai', ['headTitle' => 'Normal', 'bodyClass' => 'home', 'roomCode' => '', 'level' => '3', 'levelTxt' => 'Normal', 'randomRoom' => Room::where('pass', null)->where('host_id', null)->where('result', '=', null)->where('fen', '!=', 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1')->where('fen', 'LIKE', '% b %')->inRandomOrder()->first()]);
});
Route::get('/hard', function () {
    return view('ai', ['headTitle' => 'Hard', 'bodyClass' => 'home', 'roomCode' => '', 'level' => '4', 'levelTxt' => 'Hard', 'randomRoom' => Room::where('pass', null)->where('host_id', null)->where('result', '=', null)->where('fen', '!=', 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1')->where('fen', 'LIKE', '% b %')->inRandomOrder()->first()]);
});
Route::get('/hardest', function () {
    return view('ai', ['headTitle' => 'Hardest', 'bodyClass' => 'home', 'roomCode' => '', 'level' => '4', 'levelTxt' => 'Hardest', 'randomRoom' => Room::where('pass', null)->where('host_id', null)->where('result', '=', null)->where('fen', '!=', 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1')->where('fen', 'LIKE', '% b %')->inRandomOrder()->first()]);
});
Route::get('/about-us', function () {
    return view('about', ['headTitle' => 'About Us', 'bodyClass' => 'about', 'roomCode' => '']);
});
Route::get('/contact-us', function () {
    return view('contact', ['headTitle' => 'Contact Us', 'bodyClass' => 'contact', 'roomCode' => '']);
});
Route::get('/rooms', function () {
  return view('roomList', ['headTitle' => 'Rooms', 'bodyClass' => 'room', 'rooms' => Room::all(), 'roomCode' => '', 'randomRoom' => Room::where('pass', null)->where('host_id', null)->where('result', '=', null)->where('fen', '!=', 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1')->where('fen', 'LIKE', '% b %')->inRandomOrder()->first()]);
});
Route::get('/room/{code}', function($code) {
  return view('roomHost', ['headTitle' => 'Host - Room: '.RoomController::getRoomName($code), 'bodyClass' => 'room', 'roomCode' => $code, 'room' => Room::firstWhere('code', $code), 'randomRoom' => Room::where('pass', null)->where('host_id', null)->where('result', '=', null)->where('fen', '!=', 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1')->where('fen', 'LIKE', '% b %')->inRandomOrder()->first()]);
});
Route::get('/room/{code}/invited', function($code) {
  return view('roomGuest', ['headTitle' => 'Guest - Room: '.RoomController::getRoomName($code), 'bodyClass' => 'room', 'roomCode' => $code, 'room' => Room::firstWhere('code', $code), 'randomRoom' => Room::where('pass', null)->where('host_id', null)->where('result', '=', null)->where('fen', '!=', 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1')->where('fen', 'LIKE', '% b %')->inRandomOrder()->first()]);
});
Route::get('/room/{code}/random', function($code) {
  return view('roomGuest', ['headTitle' => 'Random - Room: '.RoomController::getRoomName($code), 'bodyClass' => 'room', 'roomCode' => $code, 'room' => Room::firstWhere('code', $code), 'randomRoom' => Room::where('pass', null)->where('host_id', null)->where('result', '=', null)->where('fen', '!=', 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1')->where('fen', 'LIKE', '% b %')->inRandomOrder()->first()]);
});
Route::get('/room/{code}/watch', function($code) {
  return view('roomWhite', ['headTitle' => 'Watch - Room: '.RoomController::getRoomName($code), 'bodyClass' => 'room', 'roomCode' => $code, 'room' => Room::firstWhere('code', $code), 'randomRoom' => Room::where('pass', null)->where('host_id', null)->where('result', '=', null)->where('fen', '!=', 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1')->where('fen', 'LIKE', '% b %')->inRandomOrder()->first()]);
});
Route::get('/room/{code}/white', function($code) {
  return view('roomWhite', ['headTitle' => 'White - Room: '.RoomController::getRoomName($code), 'bodyClass' => 'room', 'roomCode' => $code, 'room' => Room::firstWhere('code', $code), 'randomRoom' => Room::where('pass', null)->where('host_id', null)->where('result', '=', null)->where('fen', '!=', 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1')->where('fen', 'LIKE', '% b %')->inRandomOrder()->first()]);
});
Route::get('/room/{code}/black', function($code) {
  return view('roomBlack', ['headTitle' => 'Black - Room: '.RoomController::getRoomName($code), 'bodyClass' => 'room', 'roomCode' => $code, 'room' => Room::firstWhere('code', $code), 'randomRoom' => Room::where('pass', null)->where('host_id', null)->where('result', '=', null)->where('fen', '!=', 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1')->where('fen', 'LIKE', '% b %')->inRandomOrder()->first()]);
});
Route::post('/room/{code}', function($code) {
  return view('roomHost', ['headTitle' => 'Host - Room: '.RoomController::getRoomName($code), 'bodyClass' => 'room', 'roomCode' => $code, 'room' => Room::firstWhere('code', $code), 'randomRoom' => Room::where('pass', null)->where('host_id', null)->where('result', '=', null)->where('fen', '!=', 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1')->where('fen', 'LIKE', '% b %')->inRandomOrder()->first()]);
});
Route::post('/room/{code}/invited', function($code) {
  return view('roomGuest', ['headTitle' => 'Guest - Room: '.RoomController::getRoomName($code), 'bodyClass' => 'room', 'roomCode' => $code, 'room' => Room::firstWhere('code', $code), 'randomRoom' => Room::where('pass', null)->where('host_id', null)->where('result', '=', null)->where('fen', '!=', 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1')->where('fen', 'LIKE', '% b %')->inRandomOrder()->first()]);
});
Route::post('/room/{code}/random', function($code) {
  return view('roomGuest', ['headTitle' => 'Random - Room: '.RoomController::getRoomName($code), 'bodyClass' => 'room', 'roomCode' => $code, 'room' => Room::firstWhere('code', $code), 'randomRoom' => Room::where('pass', null)->where('host_id', null)->where('result', '=', null)->where('fen', '!=', 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1')->where('fen', 'LIKE', '% b %')->inRandomOrder()->first()]);
});
Route::post('/room/{code}/watch', function($code) {
  return view('roomWhite', ['headTitle' => 'Watch - Room: '.RoomController::getRoomName($code), 'bodyClass' => 'room', 'roomCode' => $code, 'room' => Room::firstWhere('code', $code), 'randomRoom' => Room::where('pass', null)->where('host_id', null)->where('result', '=', null)->where('fen', '!=', 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1')->where('fen', 'LIKE', '% b %')->inRandomOrder()->first()]);
});
Route::post('/room/{code}/white', function($code) {
  return view('roomWhite', ['headTitle' => 'White - Room: '.RoomController::getRoomName($code), 'bodyClass' => 'room', 'roomCode' => $code, 'room' => Room::firstWhere('code', $code), 'randomRoom' => Room::where('pass', null)->where('host_id', null)->where('result', '=', null)->where('fen', '!=', 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1')->where('fen', 'LIKE', '% b %')->inRandomOrder()->first()]);
});
Route::post('/room/{code}/black', function($code) {
  return view('roomBlack', ['headTitle' => 'Black - Room: '.RoomController::getRoomName($code), 'bodyClass' => 'room', 'roomCode' => $code, 'room' => Room::firstWhere('code', $code), 'randomRoom' => Room::where('pass', null)->where('host_id', null)->where('result', '=', null)->where('fen', '!=', 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1')->where('fen', 'LIKE', '% b %')->inRandomOrder()->first()]);
});
Route::get('/rooms/list', [RoomController::class, 'getRooms'])->name('rooms.list');

Route::get('/tournament', function() {
  return view('app/home', ['bodyClass' => 'dashboard', 'matchUsers' => UserController::getMatchUsers(), 'matchRooms' => RoomController::getMatchRooms(), 'playingRooms' => RoomController::getPlayingRooms(), 'playedRooms' => RoomController::getPlayedRooms(), 'rankUsers' => UserController::getRankUsers(), 'onlinePlayers' => UserController::onlinePlayers()]);
});
Route::get('/history', function() {
  return view('app/history', ['headTitle' => 'History', 'bodyClass' => 'dashboard', 'matchUsers' => UserController::getMatchUsers(), 'matchRooms' => RoomController::getMatchRooms(), 'playingRooms' => RoomController::getPlayingRooms(), 'playedRooms' => RoomController::getPlayedRooms(), 'rankUsers' => UserController::getRankUsers()]);
});
Route::get('/ranking', function() {
  return view('app/ranking', ['headTitle' => 'Ranking', 'bodyClass' => 'dashboard', 'users' => UserController::getUsers(), 'matchRooms' => RoomController::getMatchRooms(), 'rankUsers' => UserController::getRankUsers()]);
});
Route::get('/change-password', function() {
  return view('app/changePassword', ['headTitle' => 'Change password', 'bodyClass' => 'player profile', 'player' => Auth::user(), 'users' => UserController::getUsers(), 'matchRooms' => RoomController::getMatchRooms(), 'rankUsers' => UserController::getRankUsers(), 'playerRooms' => RoomController::getPlayerRooms(Auth::user()->id)]);
})->middleware('auth');
Route::get('/change-name', function() {
  return view('app/changeName', ['headTitle' => 'Change name', 'bodyClass' => 'player profile', 'player' => Auth::user(), 'users' => UserController::getUsers(), 'matchRooms' => RoomController::getMatchRooms(), 'rankUsers' => UserController::getRankUsers(), 'playerRooms' => RoomController::getPlayerRooms(Auth::user()->id)]);
})->middleware('auth');
Route::get('/my-profile', function() {
  return view('app/player', ['headTitle' => 'My profile', 'bodyClass' => 'player profile', 'player' => Auth::user(), 'users' => UserController::getUsers(), 'matchRooms' => RoomController::getMatchRooms(), 'rankUsers' => UserController::getRankUsers(), 'playerRooms' => RoomController::getPlayerRooms(Auth::user()->id)]);
})->middleware('auth');
Route::get('/player/{id}', function($id) {
  return view('app/player', ['headTitle' => 'Profile of' . ' "' . UserController::getUserName($id) . '"', 'bodyClass' => 'player', 'player' => User::firstWhere('id', $id), 'users' => UserController::getUsers(), 'matchRooms' => RoomController::getMatchRooms(), 'rankUsers' => UserController::getRankUsers(), 'playerRooms' => RoomController::getPlayerRooms($id)]);
});
Route::get('/search', 'UserController@searchPlayers')->name('searchPlayers');

Auth::routes();