<?php

namespace App\Services\Interfaces;

interface AuthInterface
{
    public function register(array $data);

    public function login(array $credentials);

    public function logout($user);

    public function sendPasswordResetLink(string $email);

    public function resetPassword(array $data);

    public function confirmPassword(string $password);
}
