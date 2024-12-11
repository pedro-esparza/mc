<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Room;
use Creativeorange\Gravatar\Facades\Gravatar;
use Carbon\Carbon;
use Avatar;

class UserController extends Controller
{

    public function updateOnlineStatus(Request $request)
    {
        $id = $request->input('id');
        if (auth()->id() == $id) {
            DB::table('users')
                ->updateOrInsert(
                ['id' => $id],
                ['last_seen_at' => Carbon::now()]
            );
        }
    }

    public static function updatePlayerOnlineStatus($id)
    {
        if (isset($id) && auth()->id() == $id) {
            DB::table('users')
                ->updateOrInsert(
                ['id' => $id],
                ['last_seen_at' => Carbon::now()]
            );
        }
    }

    public static function updatePlayerStatus($id)
    {
        DB::table('users')
            ->updateOrInsert(
            ['id' => $id],
            ['last_seen_at' => Carbon::now()]
        );
    }

    public static function updatePlayersStatus(Request $request)
    {
        $code = $request->input('room-code');
        
        $json = DB::table('rooms')
                ->select('host_id', 'guest_id')
                ->where('code', '=', $code)
                ->get();

        $data = json_decode($json, true);

        $roomIds = $data[0];

        self::updatePlayerStatus($roomIds['host_id']);
        self::updatePlayerStatus($roomIds['guest_id']);
    }

    public static function onlineStatus($id)
    {
        if (isset($id)) {
            self::updatePlayerOnlineStatus($id);
        }

        $user = User::find($id);

        if (isset($user->last_seen_at)) {
            if (Carbon::parse($user->last_seen_at)->diffInMinutes() < 2) {
                return ' <i title="Trực tuyến" class="text-success fas fa-circle"></i>';
            } else {
                return ' <i title="Ngoại tuyến" class="text-dark fas fa-circle"></i>';
            }
        } else {
            return ' <i title="Ngoại tuyến" class="text-dark fas fa-circle"></i>';
        }
    }

    public static function onlinePlayers()
    {
        $usersOnline = Cache::remember('usersOnline', 60, function () {
            $sessions = Session::all();
            return count($sessions);
        });
        return $usersOnline;
    }

    public static function renderOnlinePlayers()
    {
        $onlinePlayers = DB::table('users')
                ->where('last_seen_at', '>=', Carbon::now()->subMinutes(2))
                ->count();

        return (($onlinePlayers == 0) ? 'nobody': $onlinePlayers . ' player' . ($onlinePlayers > 1 ? 's' : '')).' online';
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8',
            'new_confirm_password' => 'required|same:new_password',
        ],
        [
            'current_password.required' => 'Mật khẩu hiện tại bắt buộc điền.',
            'new_password.required' => 'Mật khẩu mới bắt buộc điền.',
            'new_password.min' => 'Mật khẩu mới phải ít nhất 8 ký tự.',
            'new_confirm_password.required' => 'Mật khẩu lặp lại bắt buộc điền',
            'new_confirm_password.same' => 'Mật khẩu lặp lại và mật khẩu mới phải giống nhau.',
        ]);
        
        $oldId = $request->input('current_id');
        $user = User::find($oldId);

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không khớp']);
        }
        $user->password = Hash::make($request->input('new_password'));
        $user->save();

        return redirect('/thi-dau')->with('success', 'Mật khẩu đã thay đổi thành công!');
    }

    public function changeName(Request $request)
    {
        $request->validate([
            'current_name' => 'required',
            'new_name' => 'required|min:3|max:15|unique:users,name',
        ],
        [
            'current_name.required' => 'Tên hiện tại bắt buộc.',
            'new_name.required' => 'Tên mới bắt buộc điền.',
            'new_name.min' => 'Tên mới phải ít nhất 3 ký tự.',
            'new_name.max' => 'Tên mới phải ít hơn 16 ký tự.',
            'new_name.unique' => 'Tên này đã được sử dụng.',
        ]);

        $oldId = $request->input('current_id');
        $oldName = $request->input('current_name');
        $newName = $request->input('new_name');

        $user = User::find($oldId);

        $user->name = $newName;

        $user->save();

        return redirect('/thi-dau')->with('success', 'Bạn đã thay đổi tên thành công!');
    }

    public static function renderPlayerName($id)
    {
        $json = DB::table('users')
                ->select('name', 'email')
                ->where('id', '=', $id)
                ->get();

        $count = DB::table('users')
                ->where('id', '=', $id)
                ->count();

        $data = json_decode($json, true);

        $onlineStatus = self::onlineStatus($id);

        if ($count > 0) {
            return '<a class="text-dark showPromotion" target="_blank" href="'.URL::to('/player/').'/'.$id.'"><img src="'.Avatar::create($data[0]['name'])->setDimension(38)->setFontSize(19).'" />'.'# '.$id.'  '.$data[0]['name'].$onlineStatus.'</a>';
            // return '<a class="text-dark" target="_blank" href="'.URL::to('/player/').'/'.$id.'"><img src="'.Gravatar::get($data[0]['email'], ['size' => 38]).'" />'.'# '.$id.'  '.$data[0]['name'].$onlineStatus.'</a>';
        } else {
            return '<span class="waitingIndicator">
                        <span class="indicator"></span>
                        <span class="indicator"></span>
                        <span class="indicator"></span>
                        <span class="indicator"></span>
                        <span class="indicator"></span>
                    </span>';
        }
    }

    public static function renderPlayerRank($id)
    {
        $user = User::find($id);

        $rank = User::where('points', '>', $user->points)->count() + 1;

        $totalUsers = User::all()->count();

        return $rank.'/'.$totalUsers;
    }

    public static function renderPlayerEmail($id)
    {
        $user = User::find($id);

        return '<a class="text-dark showPromotion" target="_blank" href="mailto:'.$user->email.'">'.$user->email.'</a>';
    }

    public static function renderPlayerNameRoom($id)
    {
        $json = DB::table('users')
                ->select('name', 'email')
                ->where('id', '=', $id)
                ->get();

        $count = DB::table('users')
                ->where('id', '=', $id)
                ->count();

        $data = json_decode($json, true);

        $onlineStatus = self::onlineStatus($id);

        if ($count > 0) {
            return '<a class="text-dark showPromotion" target="_blank" href="'.URL::to('/player/').'/'.$id.'"><img alt="'.$data[0]['name'].'" src="'.Avatar::create($data[0]['name'])->setDimension(28)->setFontSize(14).'">'.'# '.$id.'  '.$data[0]['name'].$onlineStatus.'</a>';
            // return '<a class="text-dark" target="_blank" href="'.URL::to('/player/').'/'.$id.'"><img src="'.Gravatar::get($data[0]['email'], ['size' => 28]).'" />'.'# '.$id.'  '.$data[0]['name'].$onlineStatus.'</a>';
        } else {
            // return 'đang đợi';
            return '<span class="waitingIndicator">
                        <span class="indicator"></span>
                        <span class="indicator"></span>
                        <span class="indicator"></span>
                        <span class="indicator"></span>
                        <span class="indicator"></span>
                    </span>';
        }
    }

    public static function renderPlayersTitle(Request $request)
    {
        $code = $request->input('room-code');
        
        $json = DB::table('rooms')
                ->select('host_id', 'guest_id')
                ->where('code', '=', $code)
                ->get();

        $data = json_decode($json, true);

        $roomIds = $data[0];

        // self::updatePlayerStatus($roomIds['host_id']);
        // self::updatePlayerStatus($roomIds['guest_id']);

        $hostTitle = self::renderPlayerNameRoom($roomIds['host_id']);
        $guestTitle = self::renderPlayerNameRoom($roomIds['guest_id']);

        return '<span class="host-title">'.$hostTitle.'</span> <span class="guest-title">'.$guestTitle.'</span>';
    }

    public static function getUserName($id)
    {
        $json = DB::table('users')
                ->select('name')
                ->where('id', '=', $id)
                ->get();
        $data = json_decode($json, true);
        return $data[0]['name'];
    }

    public static function getName(Request $request)
    {
        $id = $request->input('id');
        $json = DB::table('users')
                ->select('name')
                ->where('id', '=', $id)
                ->get();
        $data = json_decode($json, true);
        return $data[0]['name'];
    }

    public static function getNameEmail(Request $request)
    {
        $id = $request->input('id');
        $json = DB::table('users')
                ->select('name', 'email')
                ->where('id', '=', $id)
                ->get();
        $data = json_decode($json, true);
        return $data[0];
    }

    public static function getPoints(Request $request)
    {
        $id = $request->input('id');
        $json = DB::table('users')
                ->select('points')
                ->where('id', '=', $id)
                ->get();
        $data = json_decode($json, true);
        return $data[0]['points'];
    }

    public function updatePoints(Request $request)
    {
        $id = $request->input('id');

        $hostPoints = DB::table('rooms')
                ->where('host_id', '=', $id)
                ->where('result', '=', '1')
                ->count();

        $guestPoints = DB::table('rooms')
                ->where('guest_id', '=', $id)
                ->where('result', '=', '-1')
                ->count();

        $hostDrawPoints = DB::table('rooms')
                ->where('host_id', '=', $id)
                ->where('result', '=', '0')
                ->count();

        $guestDrawPoints = DB::table('rooms')
                ->where('guest_id', '=', $id)
                ->where('result', '=', '0')
                ->count();

        $userPoints = 3 * ($hostPoints + $guestPoints) + $hostDrawPoints + $guestDrawPoints;

        DB::table('users')
            ->updateOrInsert(
            ['id' => $id],
            ['points' => $userPoints]
        );
    }

    public function getWinMatchPoints(Request $request)
    {
        $id = $request->input('id');

        $winHostMatchPoints = DB::table('rooms')
                ->where('host_id', '=', $id)
                ->where('result', '=', '1')
                ->count();

        $winGuestMatchPoints = DB::table('rooms')
                ->where('guest_id', '=', $id)
                ->where('result', '=', '-1')
                ->count();

        $winMatchPoints = $winHostMatchPoints + $winGuestMatchPoints;
        
        return $winMatchPoints;
    }

    public function getLoseMatchPoints(Request $request)
    {
        $id = $request->input('id');

        $loseHostMatchPoints = DB::table('rooms')
                ->where('guest_id', '=', $id)
                ->where('result', '=', '1')
                ->count();

        $loseGuestMatchPoints = DB::table('rooms')
                ->where('host_id', '=', $id)
                ->where('result', '=', '-1')
                ->count();

        $loseMatchPoints = $loseHostMatchPoints + $loseGuestMatchPoints;
        
        return $loseMatchPoints;
    }

    public static function getPlayerBoards($id)
    {
        $data = DB::table('rooms')
                ->select('fen', 'code', 'host_id', 'guest_id', 'result', 'pass', 'modified_at')
                ->orWhere('host_id', '=', $id)
                ->orWhere('guest_id', '=', $id)
                ->orderBy('modified_at', 'desc')
                ->paginate(12);
        return $data;
    }

    public function getDrawMatchPoints(Request $request)
    {
        $id = $request->input('id');

        $drawHostMatchPoints = DB::table('rooms')
                ->where('host_id', '=', $id)
                ->where('result', '=', '0')
                ->count();

        $drawGuestMatchPoints = DB::table('rooms')
                ->where('guest_id', '=', $id)
                ->where('result', '=', '0')
                ->count();

        $drawMatchPoints = $drawHostMatchPoints + $drawGuestMatchPoints;
        
        return $drawMatchPoints;
    }

    public function getTotalMatchPoints(Request $request)
    {
        $id = $request->input('id');

        $winHostMatchPoints = DB::table('rooms')
                ->where('host_id', '=', $id)
                ->where('result', '=', '1')
                ->count();

        $winGuestMatchPoints = DB::table('rooms')
                ->where('guest_id', '=', $id)
                ->where('result', '=', '-1')
                ->count();

        $loseHostMatchPoints = DB::table('rooms')
                ->where('guest_id', '=', $id)
                ->where('result', '=', '1')
                ->count();

        $loseGuestMatchPoints = DB::table('rooms')
                ->where('host_id', '=', $id)
                ->where('result', '=', '-1')
                ->count();

        $drawHostMatchPoints = DB::table('rooms')
                ->where('host_id', '=', $id)
                ->where('result', '=', '0')
                ->count();

        $drawGuestMatchPoints = DB::table('rooms')
                ->where('guest_id', '=', $id)
                ->where('result', '=', '0')
                ->count();

        $totalMatchPoints = $winHostMatchPoints + $winGuestMatchPoints + $loseHostMatchPoints + $loseGuestMatchPoints + $drawHostMatchPoints + $drawGuestMatchPoints;

        return $totalMatchPoints;
    }

    public static function updatePlayerPoints($id)
    {
        $hostPoints = DB::table('rooms')
                ->where('host_id', '=', $id)
                ->where('result', '=', '1')
                ->count();

        $guestPoints = DB::table('rooms')
                ->where('guest_id', '=', $id)
                ->where('result', '=', '-1')
                ->count();

        $hostDrawPoints = DB::table('rooms')
                ->where('host_id', '=', $id)
                ->where('result', '=', '0')
                ->count();

        $guestDrawPoints = DB::table('rooms')
                ->where('guest_id', '=', $id)
                ->where('result', '=', '0')
                ->count();

        $userPoints = 3 * ($hostPoints + $guestPoints) + $hostDrawPoints + $guestDrawPoints;

        DB::table('users')
            ->updateOrInsert(
            ['id' => $id],
            ['points' => $userPoints]
        );
    }

    public static function getUsers()
    {
        // $users = DB::table('users')
        //         ->select('id')
        //         ->get();
        
        // foreach ($users as $user) {
        //     self::updatePlayerPoints($user->id);
        // }
        
        $data = DB::table('users')
                ->select('id', 'email', 'name', 'points', 'last_seen_at', 'created_at')
                ->orderBy('points', 'desc')
                ->paginate(10);
        return $data;
    }

    public static function getMatchUsers()
    {
        // $users = DB::table('users')
        //         ->select('id')
        //         ->limit(10)
        //         ->get();
        
        // foreach ($users as $user) {
        //     self::updatePlayerPoints($user->id);
        // }

        $data = DB::table('users')
                ->select('id', 'email', 'name', 'points', 'last_seen_at', 'created_at')
                ->orderBy('points', 'desc')
                ->limit(10)
                ->get();
        return $data;
    }

    public static function getRankUsers()
    {
        // $users = DB::table('users')
        //         ->select('id')
        //         ->get();
        
        // foreach ($users as $user) {
        //     self::updatePlayerPoints($user->id);
        // }

        $data = DB::table('users')
                ->select('id')
                ->get();
        return $data;
    }

    public static function renderPoints($id)
    {
        self::updatePlayerPoints($id);

        $json = DB::table('users')
                ->select('points')
                ->where('id', '=', $id)
                ->get();
        $data = json_decode($json, true);
        return $data[0]['points'];
    }

    public static function renderWinMatchPoints($id)
    {
        self::updatePlayerPoints($id);
        
        $winHostMatchPoints = DB::table('rooms')
                ->where('host_id', '=', $id)
                ->where('result', '=', '1')
                ->count();

        $winGuestMatchPoints = DB::table('rooms')
                ->where('guest_id', '=', $id)
                ->where('result', '=', '-1')
                ->count();

        $winMatchPoints = $winHostMatchPoints + $winGuestMatchPoints;
        
        return $winMatchPoints;
    }

    public static function renderLoseMatchPoints($id)
    {
        self::updatePlayerPoints($id);
        
        $loseHostMatchPoints = DB::table('rooms')
                ->where('guest_id', '=', $id)
                ->where('result', '=', '1')
                ->count();

        $loseGuestMatchPoints = DB::table('rooms')
                ->where('host_id', '=', $id)
                ->where('result', '=', '-1')
                ->count();

        $loseMatchPoints = $loseHostMatchPoints + $loseGuestMatchPoints;
        
        return $loseMatchPoints;
    }

    public static function renderDrawMatchPoints($id)
    {
        self::updatePlayerPoints($id);
        
        $drawHostMatchPoints = DB::table('rooms')
                ->where('host_id', '=', $id)
                ->where('result', '=', '0')
                ->count();

        $drawGuestMatchPoints = DB::table('rooms')
                ->where('guest_id', '=', $id)
                ->where('result', '=', '0')
                ->count();

        $drawMatchPoints = $drawHostMatchPoints + $drawGuestMatchPoints;
        
        return $drawMatchPoints;
    }

    public static function renderTotalMatchPoints($id)
    {
        self::updatePlayerPoints($id);
        
        $winHostMatchPoints = DB::table('rooms')
                ->where('host_id', '=', $id)
                ->where('result', '=', '1')
                ->count();

        $winGuestMatchPoints = DB::table('rooms')
                ->where('guest_id', '=', $id)
                ->where('result', '=', '-1')
                ->count();

        $loseHostMatchPoints = DB::table('rooms')
                ->where('guest_id', '=', $id)
                ->where('result', '=', '1')
                ->count();

        $loseGuestMatchPoints = DB::table('rooms')
                ->where('host_id', '=', $id)
                ->where('result', '=', '-1')
                ->count();

        $drawHostMatchPoints = DB::table('rooms')
                ->where('host_id', '=', $id)
                ->where('result', '=', '0')
                ->count();

        $drawGuestMatchPoints = DB::table('rooms')
                ->where('guest_id', '=', $id)
                ->where('result', '=', '0')
                ->count();

        $totalMatchPoints = $winHostMatchPoints + $winGuestMatchPoints + $loseHostMatchPoints + $loseGuestMatchPoints + $drawHostMatchPoints + $drawGuestMatchPoints;

        return $totalMatchPoints;
    }

    public function searchPlayers(Request $request)
    {
        $query = $request->input('query');

        $results = User::where('name', 'LIKE', '%'.$query.'%')
            ->orWhere('email', 'LIKE', '%'.$query.'%')
            ->paginate(10);

        return view('app/search', compact('results'));
    }
}
