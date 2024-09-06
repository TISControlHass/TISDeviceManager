<?php

namespace Backpack\CRUD\app\Http\Middleware;

use Closure;
use Exception;
use Throwable;

class EnsureEmailVerification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // if the route is one in the verification process, do nothing
        if (in_array($request->route()->getName(), ['verification.notice', 'verification.verify', 'verification.send'])) {
            return $next($request);
        }

        // the Laravel middleware needs the user resolver to be set with the backpack guard
        $userResolver = $request->getUserResolver();
        $request->setUserResolver(function () use ($userResolver) {
            return $userResolver(backpack_guard_name());
        });

        try {
            $verifiedMiddleware = new (app('router')->getMiddleware()['verified'])();
        } catch(Throwable) {
            throw new Exception('Missing "verified" alias middleware in App/Http/Kernel.php. More info: https://backpackforlaravel.com/docs/6.x/base-how-to#enable-email-verification-in-backpack-routes');
        }

        return $verifiedMiddleware->handle($request, $next);
    }
}
