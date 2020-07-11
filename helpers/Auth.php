<?php

namespace helpers;

class Auth
{
    public static function generateCredential()
    {
        $secretKey      = config('credentialSecret', 'key');
        $password       = config('credentialSecret', 'password');
        return base64_encode("$secretKey:$password");
    }
}
