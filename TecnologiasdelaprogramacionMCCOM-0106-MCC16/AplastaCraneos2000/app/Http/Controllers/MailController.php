<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Mail;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailController extends Controller
{
    public function send(Request $request)
    {
        $name = $request->input('name');
        $email = $request->input('email');
        $message = $request->input('message');
        $subject = $request->input('subject');
        if (!$request->input('name') || $name === '') {
            echo json_encode(array('message' => 'Name cannot be empty', 'code' => 0));
            exit();
        }
        if (!$request->input('email') || $email === '') {
            echo json_encode(array('message' => 'Email cannot be empty', 'code' => 0));
            exit();
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(array('message' => 'Email format invalid', 'code' => 0));
            exit();
        }
        if (!$request->input('subject') || $subject === '') {
            echo json_encode(array('message' => 'Subject cannot be empty', 'code' => 0));
            exit();
        }
        if (!$request->input('message') || $message === '') {
            echo json_encode(array('message' => 'Message cannot be empty', 'code' => 0));
            exit();
        }
        $content = "<p>From: $name</p><p>Email: $email</p><p>Message: $message</p>";
        $recipient = "tung.42@gmail.com";
        $mail = new PHPMailer(true);     // Passing `true` enables exceptions
        try {
            // Email server settings
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->CharSet = 'UTF-8';
            $mail->Host = 'smtp.gmail.com';             //  smtp host
            $mail->SMTPAuth = true;
            $mail->Username = 'tung.42@gmail.com';   //  sender username
            $mail->Password = 'dntffroqpkqfumre';       // sender password
            $mail->SMTPSecure = 'tls';                  // encryption - ssl/tls
            $mail->Port = 587;                          // port - 587/465
            $mail->setFrom('noreply@chessroom.top', '[Chess Room] Contact form');
            $mail->addAddress($recipient);
            $mail->addReplyTo($recipient, 'Tung Pham');
            $mail->isHTML(true);                // Set email content format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $content;
            // $mail->AltBody = plain text version of email body;
            if( !$mail->send() ) {
                echo json_encode(array('message' => 'Email not sent', 'code' => 0));
                exit();
            }
            else {
                echo json_encode(array('message' => 'Email has been sent', 'code' => 1));
                exit();
            }
        } catch (Exception $e) {
            echo json_encode(array('message' => 'Message could not be sent', 'code' => 0));
            exit();
        }
    }
}
