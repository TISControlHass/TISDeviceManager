<?php

namespace Backpack\CRUD\app\Library\Auth;

use Illuminate\Support\Facades\Cookie;

class UserFromCookie
{
    public function __invoke(): ?\Illuminate\Contracts\Auth\MustVerifyEmail
    {
        if (Cookie::has('backpack_email_verification')) {
            return config('backpack.base.user_model_fqn')::where(config('backpack.base.email_column'), Cookie::get('backpack_email_verification'))->first();
        }

        return null;
    }
}
