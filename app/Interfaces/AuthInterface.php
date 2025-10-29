<?php

namespace App\Interfaces;

interface AuthInterface
{
    public function login(array $data);
    public function verifyTwoFactor(array $data);
    public function resendTwoFactorCode(array $data);
    public function forgottenPassword(array $data);
    public function otpCode(array $data);
    public function newPassword(array $data);
}
