<?php

namespace App\Http\Controllers\Auth\Views;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login() {
        return view("auth.signin");
    }

    public function showTwoFactorForm() {
        return view("auth.twofactorcode");
    }

    public function forgottenPassword() {
        return view("auth.forgottenpassword");
    }

    public function otpCode() {
        return view("auth.otpcode");
    }
    
    public function newPassword() {
        return view("auth.newpassword");
    }

}
