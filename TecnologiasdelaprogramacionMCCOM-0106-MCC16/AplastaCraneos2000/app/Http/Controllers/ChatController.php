<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Asika\Autolink\AutolinkStatic;

class ChatController extends Controller
{
    public function post() {
        session_name('ChessRoom-'.$_POST['roomCode']);
        session_start();
        if (isset($_SESSION['name'])){
            $text = htmlspecialchars($_POST['text']);
            $text = AutolinkStatic::convert($text);
            $text = AutolinkStatic::convertEmail($text);
            $roomCode = $_POST['roomCode'];
            $text_message = "<div class='msgln'><span class='chat-time'>".date("Y-m-d | H:i:s")."</span> <b class='user-name'>".$_SESSION['name']."</b> ".stripslashes($text)."<br></div>";
            file_put_contents($_POST['roomPath'], $text_message, FILE_APPEND | LOCK_EX);
        }
    }
}
