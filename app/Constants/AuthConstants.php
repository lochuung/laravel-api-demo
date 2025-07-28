<?php

namespace App\Constants;

class AuthConstants
{
    public const AUTH_COOKIE_NAME = 'access_token';
    public const AUTH_HEADER_NAME = 'Authorization';
    public const AUTH_HEADER_PREFIX = 'Bearer ';
    public const AUTH_COOKIE_PATH = '/';
    public const AUTH_COOKIE_SECURE = true;
    public const AUTH_COOKIE_HTTP_ONLY = true; // Prevents JavaScript access to the cookie
}
