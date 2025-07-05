<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Services\AuthUserService;
use Illuminate\Http\Request;
use App\Models\User;
use Log;

class AuthUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response {
		Log::debug("AuthUser Middleware");
		$user = JWTAuth::parseToken()->authenticate();
		if(!$user) {
			return response()->json(['error' => 'User not found'], 404);
		}

		Log::info("Authenticated user ID [$user->id]");

        $user = User::find($user->id);
		$timezone = $request->header('Timezone');

		Log::info("User timezone [$timezone]");

        if(!$user) throw new \Exception("ID of implementing user not found.");

        app()->instance(AuthUserService::class, new AuthUserService($user->id, $timezone));

        return $next($request);
    }
}
