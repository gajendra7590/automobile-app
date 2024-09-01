<?php

namespace App\Traits;

use App\Mail\EmialSender;
use App\Models\LoginAccessCode;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

trait EmailTrait
{
    public static function sendEmail($templateKey, $toEmail,  $subject, $data = [], $attachments = []) {
        $email_payload = ['template' => $templateKey, 'toEmail' => $toEmail, 'subject' => $subject, 'data' => $data, 'attachments' => $attachments];
        $result = null;

        $cc_emails = env('EMAIL_SEND_CC');
        $cc = [];
        if(!empty($cc_emails) || ($cc_emails != "")) {
            $cc = explode(',',$cc_emails);
        }
        $toEmail = env('EMAIL_SEND_TO');
        $result = Mail::to($toEmail)->cc($cc)->send(new EmialSender($email_payload));
        return true;
    }

    //SEND EMAIL FOR - LOGIN OTP
    public static function sendLoginOtp($user_id) {
        $userModel = User::find($user_id);
        if(!$userModel) {
            return false;
        }
        $user                = $userModel->toArray();
        $user['token']       = LoginAccessCode::createAccessToken($userModel->email);
        $toEmail             = $user['email'];
        $subject             = "LOGIN OTP FOR ASP VDMS";
        $template            = "login_otp";
        return self::sendEmail($template, $toEmail, $subject, $user);
    }

}
