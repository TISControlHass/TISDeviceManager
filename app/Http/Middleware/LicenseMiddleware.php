<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Http;
use Prologue\Alerts\Facades\Alert;
use Illuminate\Support\Facades\Redirect;

class LicenseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Checks license validity by verifying with a remote server.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Retrieve license data from cache or fetch from remote server
        $license = Cache::remember('app_license', config('license.check_interval'), function () {
            return $this->fetchLicenseFromRemote();
        });

        // Handle scenarios where license data is unavailable
        if (is_null($license)) {
            Log::error('License verification failed: unable to retrieve license data.');
            // show alert
            Alert::add("error", 'License verification failed. Please contact support.')->flash();
            return Redirect::to(backpack_url('dashboard'));
            // return Response::make('License verification failed. Please contact support.', 503);
        }

        // Check the response for validity
        if (isset($license['status'])) {
            if (!$license['status']) {
                Log::warning('License check failed: ' . ($license['message'] ?? 'Unknown error.'));
                return Response::make($license['message'] ?? 'License validation failed.', 403);
            }
        } else {
            Log::error('Invalid license data structure.');
            return Response::make('Invalid license data.', 403);
        }

        // Log successful license verification
        Log::info('License verified successfully.');

        return $next($request);
    }

    /**
     * Fetch license data from the remote license server.
     *
     * @return array|null
     */
    protected function fetchLicenseFromRemote()
    {
        try {
            // first get licence key
            // $resp = Http::get('http://homeassistant.local:8123/api/get_key')->json();
            // $key = $resp['key'];
            $key = "11:22:33:44:55:66";
            $response = Http::withToken(config('license.api_key'))
                ->get(config('license.server_url'), [
                    'mac' => $key
                ]);
            if ($response->status() === 200 && $response->json()['status'] === 'success') {
                return $response->json();
            }

            // Handle HTTP error statuses
            if ($response->status() === 401) {
                Log::error('Unauthorized access to license server.');
            } elseif ($response->status() === 404) {
                Log::error('License endpoint not found on server.');
            } else {
                Log::error('License server responded with status: ' . $response->status());
            }

            return null; // Return null if the response was not successful
        } catch (\Exception $e) {
            Log::error('Error fetching license from remote server: ' . $e->getMessage());
            return null; // Return null on error
        }
    }
}
