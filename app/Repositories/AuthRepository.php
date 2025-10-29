<?php

namespace App\Repositories;

use App\Interfaces\AuthInterface;
use App\Mail\OTPCodeMail;
use App\Mail\TwoFactorCodeMail;
use App\Models\OTPCode;
use App\Models\TwoFactorCode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthRepository implements AuthInterface
{
    // public function login(array $data)
    // {
    //     return Auth::attempt($data);
    // }


    public function login(array $data)
    {
        if (Auth::attempt($data)) {

            $user = Auth::user();

            $code = rand(111111, 999999);


            TwoFactorCode::where('email', $user->email)->delete();

            // Créer un nouveau code avec expiration de 10 minutes
            TwoFactorCode::create([
                'email' => $user->email,
                'code' => Hash::make($code),
                'expires_at' => Carbon::now()->addMinutes(10),
            ]);

            // Envoyer le code par email
            Mail::to($user->email)->send(new TwoFactorCodeMail($user->name, $user->email, $code));

            session()->put('email', $user->email);

            // Déconnecter temporairement l'utilisateur
            Auth::logout();

            return true;
        }

        return false;
    }


    public function verifyTwoFactor(array $data)
    {
        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            return false;
        }

        $twoFactorCode = TwoFactorCode::where('email', $user->email)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$twoFactorCode) {
            return false;
        }

        if (Hash::check($data['code'], $twoFactorCode->code)) {

            $twoFactorCode->delete();

            Auth::login($user);

            return true;
        }

        return false;
    }



    public function resendTwoFactorCode(array $data)
    {
        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            return false;
        }

        $code = rand(111111, 999999);

        TwoFactorCode::where('email', $user->email)->delete();

        TwoFactorCode::create([
            'email' => $user->email,
            'code' => Hash::make($code),
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        Mail::to($user->email)->send(new TwoFactorCodeMail($user->name, $user->email, $code));

        return true;
    }



    public function forgottenPassword(array $data)
    {
        $user = User::where('email', $data['email'])->first();

        if (!$user)
            return false;

        $otp_code = [
            'email' => $data['email'],
            'code' => rand(111111, 999999),
        ];

        OTPCode::where('email', $data['email'])->delete();
        OTPCode::create($otp_code);

        Mail::to($data['email'])->send(new OTPCodeMail($user->name, $data['email'], $otp_code['code']));

        session()->put('email', $data['email']);

        return true;

        return false;
    }


    public function otpCode(array $data)
    {
        $otp_code = OTPCode::where('email', $data['email'])->first();

        if ($otp_code)
            return Hash::check($data['code'], $otp_code->code);

        return false;
    }


    public function newPassword(array $data)
    {
        $user = User::where('email', session()->get('email'))->first();
        if (!$user)
            return false;

        $otp_code = OTPCode::where('email', session()->get('email'))->first();

        if (!$otp_code)
            return false;

        $user->update(['password' => $data['password']]);

        $otp_code->delete();
        session()->forget('email');

        return true;
    }

}
