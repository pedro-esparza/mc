@php
session_name('ChessRoom-'.$roomCode);
session_start();

$room_path = public_path().'/roomChatLog/'.$roomCode.'-roomchatlog.html';
$log_path = url('/').'/roomChatLog/'.$roomCode.'-roomchatlog.html';

if(!is_file($room_path)){
    $welcome_message = "<div class='msgln'><span class='chat-time'>".date("Y-m-d | H:i:s")."</span> <span class='welcome-info'>Room created</span><br></div>";
    file_put_contents($room_path, $welcome_message);
}

if(isset($_GET['logout'])){    
     
    //Simple exit message
    $logout_message = "<div class='msgln'><span class='chat-time'>".date("Y-m-d | H:i:s")."</span> <span class='left-info'>User <b class='user-name-left'>". $_SESSION['name'] ."</b> has left the chat session.</span><br></div>";
    file_put_contents($room_path, $logout_message, FILE_APPEND | LOCK_EX);
     
    // session_destroy();
    $_SESSION = [];
    header("Location: ".url()->current() ); //Redirect the user
}
 
if(isset($_POST['enter'])){
    if($_POST['name'] != ""){
        $_SESSION['name'] = stripslashes(htmlspecialchars($_POST['name']));
        setcookie('chessroom_name', $_SESSION['name']);
        $login_message = "<div class='msgln'><span class='chat-time'>".date("Y-m-d | H:i:s")."</span> <span class='enter-info'>User <b class='user-name-enter'>". $_SESSION['name'] ."</b> has entered the chat session.</span><br></div>";
        file_put_contents($room_path, $login_message, FILE_APPEND | LOCK_EX);
    }
    else{
        echo '<span class="error">Please type in a name</span>';
    }
}
 
function loginForm(){
@endphp
    <div id="loginform">
        <p>Please enter your name to Chat!</p>
        <form action="{{ url()->current() }}" method="post">
            @csrf
            <label for="name">Name &#58;</label>
            <input type="text" name="name" id="name" value="{{ isset($_COOKIE['chessroom_name']) ? $_COOKIE['chessroom_name'] : '' }}" />
            <input type="submit" name="enter" id="enter" value="Enter" />
        </form>
    </div>
@php
}
@endphp 
<style>
#loginform form, #chat-wrapper form {
    padding: 9px 0;
    display: flex;
    gap: 10px;
    font-size: 14px;
    justify-content: center;
}

#loginform form label, #chat-wrapper form label {
    font-size: 14px;
    font-weight: bold;
    margin-top: 5px;
}

#chat-wrapper,
#loginform {
    margin: 0 auto;
    padding-bottom: 0;
    background: #eee;
    width: 600px;
    max-width: 100%;
    border: 2px solid #212121;
    border-radius: 4px;
    color: #212121;
}
   
#loginform {
    padding-top: 18px;
    text-align: center;
    border: none;
    font-size: 14px;
}
   
#loginform p {
    padding: 0;
    font-size: 14px;
    font-weight: bold;
}
   
#chatbox {
    text-align: left;
    margin: 0 auto;
    padding: 10px;
    background: #fff;
    height: 300px;
    width: calc(100% - 16px);
    border: 1px solid #a7a7a7;
    overflow: auto;
    border-radius: 4px;
    border-bottom: 4px solid #a7a7a7;
}

#loginform + #chatbox {
    margin-bottom: 10px !important;
}

#usermsg {
    flex: 1;
    border-radius: 4px;
    border: 1px solid #ff9800;
    margin-left: 9px;
    width: calc(50% - 20px) !important;
}
   
#name {
    border-radius: 4px;
    border: 1px solid #ff9800;
    padding: 2px 8px;
    font-size: 14px;
    width: calc(50% - 20px) !important;
}
   
#submitmsg,
#enter{
    background: #ff9800;
    border: 2px solid #e65100;
    color: white;
    padding: 4px 10px;
    font-weight: bold;
    border-radius: 4px;
    font-size: 14px;
    margin-right: 9px;
}
   
.error {
    color: #ff0000;
    width: 100%;
    text-align: center;
}
   
#menu {
    padding: 9px;
    display: flex;
}
   
#menu p.welcome {
    flex: 1;
}
   
a#exit {
    color: white;
    background: #c62828;
    padding: 4px 8px;
    border-radius: 4px;
    font-weight: bold;
}
   
.msgln {
    margin: 0 0 5px 0;
}
   
.msgln span.welcome-info {
    color: goldenrod;
}

.msgln span.left-info {
    color: orangered;
}

.msgln span.enter-info {
    color: green;
}
   
.msgln span.chat-time {
    color: #666;
    font-size: 60%;
}
   
.msgln b.user-name, .msgln b.user-name-left, .msgln b.user-name-enter {
    font-weight: bold;
    background: #546e7a;
    color: white;
    padding: 2px 4px;
    font-size: 90%;
    border-radius: 4px;
    margin: 0 5px 0 0;
}

.msgln b.user-name-left {
    background: orangered;
}

.msgln b.user-name-enter {
    background: green;
}
@media only screen and (min-width: 1200px) {
    #loginform {
        float: right;
        position: relative;
        font-size: 10px;
        border: none;
    }
    #loginform p {
        padding: 0;
        font-size: 14px;
    }
    #name {
        padding: 0;
        font-size: 12px;
    }
    #loginform form label, #chat-wrapper form label {
        font-size: 14px;
    }
    .error {
        position: absolute;
        float: right;
        right: 70px;
        top: 120px;
        width: auto;
    }
    #chat-wrapper {
        position: absolute;
        float: right;
        right: 0;
        top: 152px;
        width: 290px;
    }
    #chatbox {
        width: 270px;
        margin-bottom: 0px !important;
    }
    #loginform + #chatbox {
        margin-bottom: 10px !important;
    }
    #usermsg {
        margin-left: 9px;
    }
    #submitmsg {
        margin-right: 9px;
    }
    #menu {
        padding: 9px;
    }
}
</style>
@php
if(!isset($_SESSION['name'])){
@endphp
<div id="chat-wrapper">
@php
loginForm();
@endphp
    <div id="chatbox">
    @php
    if(file_exists($log_path) && filesize($log_path) > 0){
        $contents = file_get_contents($log_path);          
        echo $contents;
    }
    @endphp
    </div>
</div>
@php
}
else {
@endphp
<div id="chat-wrapper">
    <div id="menu">
        <p class="welcome">Welcome, <b>@php echo $_SESSION['name']; @endphp</b></p>
        <p class="logout"><a id="exit" href="javascript:void(0);">Exit Chat</a></p>
    </div>

    <div id="chatbox">
    @php
    if(file_exists($log_path) && filesize($log_path) > 0){
        $contents = file_get_contents($log_path);          
        echo $contents;
    }
    @endphp
    </div>

    <form name="message">
        <input name="usermsg" type="text" id="usermsg" required="required" />
        <input name="submitmsg" type="submit" id="submitmsg" value="Send" />
    </form>
</div>
@php
}
@endphp
<script type="text/javascript">
// jQuery Document
$(document).ready(function () {
    $("#submitmsg").click(function (e) {
        e.preventDefault();
        if ($("#usermsg").val() != '') {
            var clientmsg = $("#usermsg").val();
            $.post("{{ url('/api') }}/postChat", { roomCode: "{{ $roomCode }}", text: clientmsg, roomPath:  "{{ $room_path }}" });
        } else {
            bootbox.alert({
                message: "Please input message",
                size: 'small',
                centerVertical: true,
                buttons: {
                    ok: {
                        className: 'btn-success'
                    }
                }
            });
        }
        $("#usermsg").val("");
        return false;
    });

    function loadLog() {
        var oldscrollHeight = $("#chatbox")[0].scrollHeight - 20; //Scroll height before the request

        $.ajax({
            url: "{{ $log_path }}",
            cache: false,
            success: function (html) {
                $("#chatbox").html(html); //Insert chat log into the #chatbox div

                //Auto-scroll           
                var newscrollHeight = $("#chatbox")[0].scrollHeight - 20; //Scroll height after the request
                if(newscrollHeight > oldscrollHeight){
                    $("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal'); //Autoscroll to bottom of div
                }   
            }
        });
    }

    setInterval (loadLog, 1000);

    $("#exit").click(function () {
        bootbox.confirm({
            message: "Are you sure you want to end the session?",
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-light'
                }
            },
            callback: function (result) {
                if (result == true) {
                    window.location = "{{ url()->current() }}?logout=true";
                }
            }
        });
    });
});
</script>