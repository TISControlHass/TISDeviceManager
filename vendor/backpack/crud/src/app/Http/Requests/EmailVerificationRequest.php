<?php

namespace Backpack\CRUD\app\Http\Requests;

use Backpack\CRUD\app\Library\Auth\UserFromCookie;
use Illuminate\Foundation\Auth\EmailVerificationRequest as OriginalEmailVerificationRequest;

class EmailVerificationRequest extends OriginalEmailVerificationRequest
{
    public function user($guard = null)
    {
        return parent::user(backpack_guard_name()) ?? (new UserFromCookie())();
    }
}
