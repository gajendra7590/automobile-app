<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginAccessCode extends Model
{
    use HasFactory;

    protected $table = "login_access_code";

    protected $fillable = [
        'login_email',
        'login_code',
        'expiry_date'
    ];

    protected $hidden = [];

    protected $casts = [];

    public static function createAccessToken($email) {
        $SixDigitOTP =  rand(111111, 999999);
        $check = LoginAccessCode::where(['login_code' => $SixDigitOTP])->count();
        if($check) {
            $SixDigitOTP =  rand(111111, 999999);
        }
        $expiry_time = date('Y-m-d H:i:s',strtotime('+10 minutes'));
        LoginAccessCode::create(['login_email' => $email,'login_code' => $SixDigitOTP,'expiry_date' => $expiry_time]);

        //DELETE EXPIRED TOKEN
        self::deleteAccessToken();

        return $SixDigitOTP;
    }


    public static function verifyAccessToken($email, $code) {
        $result = LoginAccessCode::where(['login_email' => $email,'login_code' => $code])->where('expiry_date' ,'>', date('Y-m-d H:i:s'))->first();
        if($result) {
            self::deleteAccessToken();
            return true;
        } else {
            self::deleteAccessToken();
            return false;
        }
    }

    public static function deleteAccessToken() {
        LoginAccessCode::where('expiry_date' ,'<', date('Y-m-d H:i:s'))->delete();
        return true;
    }

}
