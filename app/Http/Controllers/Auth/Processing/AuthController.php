<?php

namespace App\Http\Controllers\Auth\Processing;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequests\ForgottenPasswordRequest;
use App\Http\Requests\AuthRequests\NewPasswordRequest;
use App\Http\Requests\AuthRequests\OTPCodeRequest;
use App\Http\Requests\AuthRequests\SigninRequest;
use App\Http\Requests\AuthRequests\TwoFactorRequest;
use App\Interfaces\AuthInterface;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    private AuthInterface $authInterface;

    public function __construct(AuthInterface $authInterface)
    {
        $this->authInterface = $authInterface;
    }

    public function login(SigninRequest $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        try {

            $result = $this->authInterface->login($data);

            if (!$result)
                return back()->withErrors([
                    'error' => 'E-mail ou mot de passe invalide•s.',
                ])->withInput();

            return redirect()->route('auth.two-factor');;
        } catch (\Exception $ex) {
            // return $ex;
            return back()->withErrors([
                'error' => 'Une erreur est survenue lors du traitement, Réessayez !'
            ])->withInput();
        }
    }

    public function verifyTwoFactor(TwoFactorRequest $request)
    {
        $data = [
            'email' => session()->get('email'),
            'code' => $request->code,
        ];

        try {
            $verified = $this->authInterface->verifyTwoFactor($data);

            if (!$verified)
                return back()->withErrors([
                    'error' => 'Code de vérification invalide ou expiré.'
                ])->withInput();

            // Supprimer la session temporaire
            session()->forget('two_factor_user_id');

            return redirect()->route('dashboard');
        } catch (\Exception $ex) {
            return back()->withErrors([
                'error' => 'Une erreur est survenue lors du traitement, Réessayez !'
            ])->withInput();
        }
    }

    public function resendTwoFactorCode()
    {
        $data = [
            'email' => session()->get('email'),
        ];
        
        try {

            if (!$data) {
                return redirect()->route('login');
            }

            $result = $this->authInterface->resendTwoFactorCode($data);

            if (!$result)
                return back()->withErrors([
                    'error' => 'Impossible de renvoyer le code.'
                ]);

            return back()->with('success', 'Un nouveau code a été envoyé à votre adresse e-mail.');
        } catch (\Exception $ex) {
            return back()->withErrors([
                'error' => 'Une erreur est survenue lors du traitement, Réessayez !'
            ]);
        }
    }

    public function forgottenPassword(ForgottenPasswordRequest $request)
    {

        $data = [
            'email' => $request->email,
        ];

        try {

            $user = $this->authInterface->forgottenPassword($data);

            if (!$user)
                return back()->withErrors([
                    'error' => 'E-mail non touvé.',
                ])->withInput();

            return redirect()->route("auth.otpcode");
        } catch (\Exception $ex) {
            // return $ex;
            return back()->withErrors([
                'error' => 'Une erreur est survenue lors du traitement, Réessayez !'
            ])->withInput();
        }
    }

    public function otpCode(OTPCodeRequest $request)
    {
        $data = [
            'email' => session()->get('email'),
            'code' => $request->code,
        ];

        try {

            $otp_code = $this->authInterface->otpCode($data);

            if (!$otp_code)
                return back()->withErrors([
                    'error' => 'Code de confirmation invalide.'
                ])->withInput();

            return redirect()->route("auth.newpassword");
        } catch (\Exception $ex) {
            return $ex;
            return back()->withErrors([
                'error' => 'Une erreur est survenue lors du traitement, Réessayez !'
            ])->withInput();
        }
    }

    public function newPassword(NewPasswordRequest $request)
    {
        $data = [
            'password' => $request->password,
        ];

        try {

            $otp_code = $this->authInterface->newPassword($data);

            if (!$otp_code)
                return back()->withErrors([
                    'error' => 'Impossible de mettre à jour le mot de passe. Prière de réessayer !'
                ])->withInput();

            return redirect()->route("login")->with("success", "Mot de passe mis à jour avec succès. Vous pouvez vous connecter maintenant.");
        } catch (\Exception $ex) {
            return $ex;
            return back()->withErrors([
                'error' => 'Une erreur est survenue lors du traitement, Réessayez !'
            ])->withInput();
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route("login");
    }
}
