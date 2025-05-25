<?php

namespace App\Services;

class PasswordService {

    /**
     * The function generates a random password of 10 characters using a specified set of characters.
     * 
     * @return string A randomly generated password consisting of 10 characters from the specified set
     * of characters (numbers, lowercase letters, uppercase letters, and special characters).
     */
    public static function generateRandomPassword( int $length = 12) : string 
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_+-=[]{}|;:,.<>?';
        $result = '';

        for ($i = 0; $i < $length; $i++) {
            $result .= $characters[random_int(0, strlen($characters) - 1)];
        }
        
        return $result;
    }
}