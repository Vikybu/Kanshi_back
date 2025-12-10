<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginRepository 
{
    public function checkCredentials(int $registration_number, string $password): ?User
    {
        $user = User::where('registration_number', $registration_number)->first();
         
        if (!$registration_number) {
            return null;
        }

        if (!Hash::check($password, $user->password)){
            return null;
        }

        return $user;

    }
}
