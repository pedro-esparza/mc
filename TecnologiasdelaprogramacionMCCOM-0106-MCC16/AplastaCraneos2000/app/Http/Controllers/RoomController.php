<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;
use DataTables;

class RoomController extends Controller
{
    public function getRooms(Request $request)
    {
        if ($request->ajax()) {
            // $data = Room::orderBy('modified_at', 'desc')->get();
            $rooms = Room::select(['fen', 'code', 'host_id', 'guest_id', 'result', 'name', 'pass', 'modified_at']);
            return Datatables::of($rooms)
                ->addColumn('code', function($row){
                    if (!isset($row->host_id)) {
                        if ($row->fen == env('INITIAL_FEN') || str_contains($row->fen, ' w ')) {
                            $roomCode = '<a class="text-dark disabled" style="cursor: default !important; text-decoration: none !important;" data-fen="'.$row->fen.'" data-code="'.$row->code.'" href="javascript:void(0)">'.((isset($row->name) && $row->name != '') ? $row->name: $row->code).'</a>';
                            if ($row->pass == '') {
                                $roomCode .= '<i class="ml-3 far fa-globe text-dark" data-toggle="tooltip" data-placement="top" data-original-title="Public"></i>';
                            } else {
                                $roomCode .= '<i class="ml-3 far fa-lock text-dark" data-toggle="tooltip" data-placement="top" data-original-title="Private"></i>';
                            }
                        } else {
                            $roomCode = '<a style="color: #222222 !important; cursor: default !important; text-decoration: none !important;" class="disabled" data-fen="'.$row->fen.'" data-code="'.$row->code.'" href="javascript:void(0)">'.((isset($row->name) && $row->name != '') ? $row->name: $row->code).'</a>';
                            if ($row->pass == '') {
                                $roomCode .= '<i class="ml-3 far fa-globe text-dark" data-toggle="tooltip" data-placement="top" data-original-title="Public"></i>';
                            } else {
                                $roomCode .= '<i class="ml-3 far fa-lock text-dark" data-toggle="tooltip" data-placement="top" data-original-title="Private"></i>';
                            }
                        }
                    } else {
                        if (auth()->check()) {
                            if (isset($row->result)) {
                                $roomCode = '<a class="text-dark showPromotion" href="javascript:void(0)" style="color: #222!important; cursor: default !important; text-decoration: none !important;" data-fen="'.$row->fen.'" data-code="'.$row->code.'">'.((isset($row->name) && $row->name != '') ? $row->name: $row->code).'</a><i class="ml-3 far fa-archive text-dark" data-toggle="tooltip" data-placement="top" data-original-title="Finished"></i>';
                            } else {
                                $roomCode = '<a class="text-warning" href="javascript:joinMatch(`'.$row->code.'`)" data-fen="'.$row->fen.'" data-code="'.$row->code.'">'.((isset($row->name) && $row->name != '') ? $row->name: $row->code).'</a><i class="ml-3 far fa-mouse text-warning" data-toggle="tooltip" data-placement="top" data-original-title="Play now"></i>';
                            }
                        } else {
                            if (str_contains($row->fen, ' w ')) {
                                $roomCode = '<a style="cursor: default !important; text-decoration: none !important;" class="text-dark" href="javascript:void(0)" data-fen="'.$row->fen.'" data-code="'.$row->code.'">'.((isset($row->name) && $row->name != '') ? $row->name: $row->code).'</a><i class="ml-3 far fa-sign-in text-dark" data-toggle="tooltip" data-placement="top" data-original-title="Login"></i>';
                            } else if (str_contains($row->fen, ' b ')) {
                                $roomCode = '<a style="cursor: default !important; text-decoration: none !important;" class="text-dark" href="javascript:void(0)" data-fen="'.$row->fen.'" data-code="'.$row->code.'">'.((isset($row->name) && $row->name != '') ? $row->name: $row->code).'</a><i class="ml-3 far fa-sign-in text-dark" data-toggle="tooltip" data-placement="top" data-original-title="Login"></i>';
                            }
                        }
                    }
                    return $roomCode;
                })
                ->addColumn('turn', function($row){
                    if (str_contains($row->fen, ' w ')) {
                        $playerTurn = '<span class="text-dark">White</span>';
                    } else if (str_contains($row->fen, ' b ')) {
                        $playerTurn = '<span class="text-dark">Black</span>';
                    }
                    return $playerTurn;
                })
                ->addColumn('result', function($row){
                    if (isset($row->result)) {
                        switch ($row->result) {
                            case '-1':
                                $roomResult = 'Guest won';
                                break;
                            case '0':
                                $roomResult = 'Drawed';
                                break;
                            case '1':
                                $roomResult = 'Host won';
                                break;
                        }
                    } else if ($row->fen == env('INITIAL_FEN')) {
                        $roomResult = '<span class="text-secondary">Not started</span>';
                    } else {
                        $roomResult = 'Ongoing';
                    }
                    return $roomResult;
                })
                ->addColumn('join', function($row){
                    if (!isset($row->host_id)) {
                        if ($row->fen == env('INITIAL_FEN')) {
                            $actionBtn = '<a class="btn btn-light text-dark mr-1 showPromotion" style="width: 100px;" data-fen="'.$row->fen.'" data-code="'.$row->code.'" href="'.URL::to('/').'/room/'.$row->code.'/white"><i class="far fa-sign-in-alt"></i> Enter</a>';
                            if ($row->pass == '') {
                                $actionBtn .= '<a class="btn btn-light text-dark watch-btn showPromotion" data-fen="'.$row->fen.'" data-code="'.$row->code.'" href="'.URL::to('/').'/room/'.$row->code.'/watch" data-toggle="tooltip" data-placement="top" data-original-title="Public"><i class="far fa-globe"></i> Watch</a>';
                            } else {
                                $actionBtn .= '<a class="btn btn-dark text-light watch-btn showPromotion" data-fen="'.$row->fen.'" data-code="'.$row->code.'" href="'.URL::to('/').'/room/'.$row->code.'/watch" data-toggle="tooltip" data-placement="top" data-original-title="Private"><i class="far fa-lock"></i> Watch</a>';
                            }
                        } else {
                            if (isset($row->result)) {
                                $actionBtn = '<a class="btn btn-dark text-light mr-1" style="width: 100px; cursor: not-allowed !important;" data-fen="'.$row->fen.'" data-code="'.$row->code.'" href="javascript:void(0);"><i class="far fa-ban"></i> Finished</a>';
                            } else {
                                if (str_contains($row->fen, ' b ')) {
                                    $actionBtn = '<a class="btn btn-dark text-light mr-1 showPromotion" style="width: 100px;" data-fen="'.$row->fen.'" data-code="'.$row->code.'" href="'.URL::to('/').'/room/'.$row->code.'/black"><i class="far fa-sign-in-alt"></i> Enter</a>';
                                } else if (str_contains($row->fen, ' w ')) {
                                    $actionBtn = '<a class="btn btn-light text-dark mr-1 showPromotion" style="width: 100px;" data-fen="'.$row->fen.'" data-code="'.$row->code.'" href="'.URL::to('/').'/room/'.$row->code.'/white"><i class="far fa-sign-in-alt"></i> Enter</a>';
                                }
                            }
                            if ($row->pass == '') {
                                $actionBtn .= '<a class="btn btn-light text-dark watch-btn showPromotion" data-fen="'.$row->fen.'" data-code="'.$row->code.'" href="'.URL::to('/').'/room/'.$row->code.'/watch" data-toggle="tooltip" data-placement="top" data-original-title="Public"><i class="far fa-globe"></i> Watch</a>';
                            } else {
                                $actionBtn .= '<a class="btn btn-dark text-light watch-btn showPromotion" data-fen="'.$row->fen.'" data-code="'.$row->code.'" href="'.URL::to('/').'/room/'.$row->code.'/watch" data-toggle="tooltip" data-placement="top" data-original-title="Private"><i class="far fa-lock"></i> Watch</a>';
                            }
                        }
                    } else {
                        if (auth()->check()) {
                            if (isset($row->result)) {
                                $actionBtn = '<a class="btn btn-dark text-light showPromotion" style="width: 190px;" href="'.URL::to('/').'/room/'.$row->code.'/watch"><i class="far fa-archive"></i> Finished</a>';
                            } else {
                                $actionBtn = '<a class="btn btn-dark text-light pulse-dark" style="width: 190px;" href="javascript:joinMatch(`'.$row->code.'`)"><i class="far fa-mouse"></i> Play now</a>';
                            }
                        } else {
                            if (str_contains($row->fen, ' w ')) {
                                $actionBtn = '<a class="btn btn-dark text-light showPromotion pulse-dark" style="width: 190px;" href="'.URL::to('/login/').'"><i class="far fa-sign-in"></i> Login</a>';
                            } else if (str_contains($row->fen, ' b ')) {
                                $actionBtn = '<a class="btn btn-dark text-light showPromotion pulse-dark" style="width: 190px;" href="'.URL::to('/login/').'"><i class="far fa-sign-in"></i> Login</a>';
                            }
                        }
                    }
                    $actionBtn .= '<a class="ml-1 btn btn-light text-dark previewBtn"><i class="far fa-eye""></i> Preview</a>';
                    return $actionBtn;
                })
                ->addColumn('time', function($row){
                    return date('Y-m-d | H:i:s', strtotime($row->modified_at));
                })
                ->escapeColumns([])
                ->orderColumn('code', 'code $1')
                ->orderColumn('result', 'result $1')
                ->orderColumn('time', 'modified_at $1')
                ->filterColumn('code', function($query, $keyword) {
                    $query->where(function($query) use ($keyword) {
                        $query->orWhere('code', 'like', '%' . $keyword . '%')
                              ->orWhere('name', 'like', '%' . $keyword . '%');
                    });
                })
                ->filterColumn('time', function($query, $keyword) {
                    $sql = "modified_at like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->rawColumns(['code', 'turn', 'result', 'join', 'time'])
                ->make(true);
        }
    }
    
    public static function getLatestRoom(Request $request)
    {
        $offsetNumber = $request->input('offset');
        $latestRoom = Room::where('pass', NULL)->where('host_id', NULL)->where('result', NULL)->orderBy('modified_at', 'desc')->offset($offsetNumber)->first();
        if ($latestRoom != null) {
            if (str_contains($latestRoom->fen, ' w ')) {
                return response()->json([
                    'color' => 'white',
                    'room' => $latestRoom
                ]);
            } else if (str_contains($latestRoom->fen, ' b ')) {
                return response()->json([
                    'color' => 'black',
                    'room' => $latestRoom
                ]);
            }
        } else {
            return response()->json([
                'room' => null
            ]);
        }
    }

    public static function getNewRoom()
    {
        $firstRoom = Room::where('fen', env('INITIAL_FEN'))->where('pass', NULL)->where('host_id', NULL)->where('result', NULL)->orderBy('modified_at', 'desc')->first();
        return response()->json([
            'room' => $firstRoom
        ]);
    }

    public static function hasRoomcode(Request $request)
    {
        $code = $request->input('room-code');

        $roomcodeCount = Room::where('code', '=', $code)
                ->count();

        if ($roomcodeCount > 0) {
            return 'yes';
        } else if ($roomcodeCount == 0) {
            return 'no';
        }
    }

    public function join(Request $request)
    {
        $code = $request->input('room-code');
        $guest_id = $request->input('guest_id');
        Room::updateOrInsert(
            ['code' => $code],
            ['guest_id' => $guest_id, 'modified_at' => date('Y-m-d H:i:s')]
        );
    }

    public static function getRoomIds(Request $request)
    {
        $code = $request->input('room-code');
        $json = Room::select('host_id', 'guest_id')
                ->where('code', '=', $code)
                ->get();
        $data = json_decode($json, true);
        return $data[0];
    }

    public static function getMatchRooms()
    {
        $data = Room::select('fen', 'code', 'host_id', 'guest_id', 'result', 'name', 'pass', 'modified_at')
                ->where('host_id', '!=', NULL)
                ->orderBy('modified_at', 'desc')
                ->paginate(10);
        return $data;
    }

    public static function getPlayingRooms()
    {
        $data = Room::select('fen', 'code', 'host_id', 'guest_id', 'result', 'name', 'pass', 'modified_at')
                ->where('host_id', '!=', NULL)
                ->where('result', '=', NULL)
                ->orderBy('modified_at', 'desc')
                ->paginate(10);
        return $data;
    }

    public static function getPlayedRooms()
    {
        $data = Room::select('fen', 'code', 'host_id', 'guest_id', 'result', 'name', 'pass', 'modified_at')
                ->where('host_id', '!=', NULL)
                ->where('result', '!=', NULL)
                ->orderBy('modified_at', 'desc')
                ->paginate(10);
        return $data;
    }

    public static function getPlayerRooms($id)
    {
        $data = Room::select('fen', 'code', 'host_id', 'guest_id', 'result', 'name', 'pass', 'modified_at')
                ->orWhere('host_id', '=', $id)
                ->orWhere('guest_id', '=', $id)
                ->orderBy('modified_at', 'desc')
                ->paginate(10);
        return $data;
    }


    public function updateResult(Request $request)
    {
        $code = $request->input('room-code');
        $result = $request->input('result');
        $auth_id = $request->input('id');
        Room::updateOrInsert(
            ['code' => $code],
            ['result' => $result, 'modified_at' => date('Y-m-d H:i:s')]
        );
        $json = Room::select('host_id', 'guest_id')
                ->where('code', '=', $code)
                ->get();
        $data = json_decode($json, true);
        $host_id = $data[0]['host_id'];
        $guest_id = $data[0]['guest_id'];

        $success_message = '';

        if ($auth_id == $host_id) {
            switch ($result) {
                case '-1':
                    $success_message = 'Host lost, no point given!';
                    break;
                case '0':
                    $success_message = 'Drawed, both sides got 1 point.';
                    break;
                case '1':
                    $success_message = 'Host won, 3 poins given! Congrats!';
                    break;
            }
        } elseif ($auth_id == $guest_id) {
            switch ($result) {
                case '-1':
                    $success_message = 'Guest won, 3 poins given! Congrats!';
                    break;
                case '0':
                    $success_message = 'Drawed, both sides got 1 point.';
                    break;
                case '1':
                    $success_message = 'Guest lost, no point given!';
                    break;
            }
        }
        return response()->json([
            'success' => $success_message
        ]);
    }

    public function updateSideResult(Request $request)
    {
        $code = $request->input('room-code');
        $result = $request->input('result');
        $lang = $request->input('lang');
        $side = $request->input('side');
        Room::updateOrInsert(
            ['code' => $code],
            ['result' => $result, 'modified_at' => date('Y-m-d H:i:s')]
        );

        $success_message = '';

        switch ($lang) {
            case 'vi':
                if ($side == 'white') {
                    switch ($result) {
                        case '-1':
                            $success_message = 'Chủ phòng thua! Cố lên nhé!';
                            break;
                        case '0':
                            $success_message = 'Hòa.';
                            break;
                        case '1':
                            $success_message = 'Chủ phòng thắng. Xin chúc mừng!';
                            break;
                    }
                } elseif ($side == 'black') {
                    switch ($result) {
                        case '-1':
                            $success_message = 'Khách thắng. Xin chúc mừng!';
                            break;
                        case '0':
                            $success_message = 'Hòa.';
                            break;
                        case '1':
                            $success_message = 'Khách thua! Cố lên nhé!';
                            break;
                    }
                }
                break;
            case 'en':
                if ($side == 'white') {
                    switch ($result) {
                        case '-1':
                            $success_message = 'Host lost!';
                            break;
                        case '0':
                            $success_message = 'Draw.';
                            break;
                        case '1':
                            $success_message = 'Host won!';
                            break;
                    }
                } elseif ($side == 'black') {
                    switch ($result) {
                        case '-1':
                            $success_message = 'Guest won!';
                            break;
                        case '0':
                            $success_message = 'Draw.';
                            break;
                        case '1':
                            $success_message = 'Guest lost!';
                            break;
                    }
                }
                break;
            case 'ja':
                if ($side == 'white') {
                    switch ($result) {
                        case '-1':
                            $success_message = 'ホストが切断されました！';
                            break;
                        case '0':
                            $success_message = '描く';
                            break;
                        case '1':
                            $success_message = 'ホストが勝ちました！';
                            break;
                    }
                } elseif ($side == 'black') {
                    switch ($result) {
                        case '-1':
                            $success_message = 'ゲストが勝ちました！';
                            break;
                        case '0':
                            $success_message = '描く';
                            break;
                        case '1':
                            $success_message = 'ゲストが負けました！';
                            break;
                    }
                }
                break;
            case 'ko':
                if ($side == 'white') {
                    switch ($result) {
                        case '-1':
                            $success_message = '호스트가 패배했습니다!';
                            break;
                        case '0':
                            $success_message = '그리다';
                            break;
                        case '1':
                            $success_message = '호스트가 이겼습니다!';
                            break;
                    }
                } elseif ($side == 'black') {
                    switch ($result) {
                        case '-1':
                            $success_message = '게스트가 이겼습니다!';
                            break;
                        case '0':
                            $success_message = '그리다';
                            break;
                        case '1':
                            $success_message = '게스트가 졌습니다!';
                            break;
                    }
                }
                break;
            case 'zh':
                if ($side == 'white') {
                    switch ($result) {
                        case '-1':
                            $success_message = '主机失败了！';
                            break;
                        case '0':
                            $success_message = '画';
                            break;
                        case '1':
                            $success_message = '主机获胜了！';
                            break;
                    }
                } elseif ($side == 'black') {
                    switch ($result) {
                        case '-1':
                            $success_message = '客人获胜了！';
                            break;
                        case '0':
                            $success_message = '画';
                            break;
                        case '1':
                            $success_message = '客人失败了！';
                            break;
                    }
                }
                break;
        }
        return response()->json([
            'success' => $success_message
        ]);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $code = $request->input('room-code');
        $name = $request->input('room-name');
        $fen = $request->input('FEN');
        $host_id = $request->input('host_id');
        $pass = $request->input('pass');
        Room::updateOrInsert(
            ['code' => $code],
            ['fen' => $fen, 'host_id' => $host_id, 'name' => $name, 'pass' => $pass, 'modified_at' => date('Y-m-d H:i:s')]
        );
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $code = $request->input('room-code');
        $fen = $request->input('FEN');
        Room::updateOrInsert(
            ['code' => $code],
            ['fen' => $fen, 'modified_at' => date('Y-m-d H:i:s')]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function setWhiteName(Request $request)
    {
        $code = $request->input('room-code');
        $whiteName = $request->input('white-name');
        Room::updateOrInsert(
            ['code' => $code],
            ['white_name' => $whiteName]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function setBlackName(Request $request)
    {
        $code = $request->input('room-code');
        $blackName = $request->input('black-name');
        Room::updateOrInsert(
            ['code' => $code],
            ['black_name' => $blackName]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function show(Room $room, $code)
    {
        if (auth()->check()) {
            // Update user's online status
            auth()->user()->update(['last_seen_at' => now()]);
        }
    
        $fen = Room::where('code', $code)->value('fen');
    
        return $fen;
    }

    public static function getRoomName($code)
    {
        $name = Room::where('code', $code)->value('name');
    
        return $name;
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function getPass(Room $room, $code)
    {
        $passJson = Room::select('pass')
                ->where('code', '=', $code)
                ->get();
        $pass = json_decode($passJson, true);
        return $pass[0]['pass'];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function changePass(Request $request, Room $room)
    {
        $code = $request->input('room-code');
        $pass = $request->input('pass');
        if (!$request->input('pass') || $pass === '') {
            echo json_encode(array('message' => 'Password cannot be empty', 'code' => 0));
            exit();
        } else {
            DB::update('update rooms set pass = ? where code = ?', [$pass, $code]);
            echo json_encode(array('message' => 'Changed password successfully!', 'code' => 1));
            exit();
        }
    }
    
    public function getEventStream(Room $room, $code) {
        $fenJson = Room::select('fen')
                    ->where('code', '=', $code)
                    ->get();
        $fen = json_decode($fenJson, true)[0]['fen'];

        $response = new StreamedResponse();
        $response->setCallback(function () use ($fen){
            echo 'data: ' . $fen . "\n\n";
            ob_flush();
            flush();
            usleep(1000000);
            //usleep(2500000);
        });

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('X-Accel-Buffering', 'no');
        $response->headers->set('Cach-Control', 'no-cache');
        $response->send();
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function edit(Room $room)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Room $room)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function destroy(Room $room)
    {
        //
    }
}
