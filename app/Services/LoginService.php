<?php

namespace App\Services;

use App\Repositories\LoginRepository;

class LoginService 
{
    protected $loginRepository;

    public function __construct(LoginRepository $loginRepository)
    {
        $this->loginRepository = $loginRepository;
    }

    public function login(int $registration_number, string $password)
    {
        return $this->loginRepository->checkCredentials($registration_number, $password);
    }
}