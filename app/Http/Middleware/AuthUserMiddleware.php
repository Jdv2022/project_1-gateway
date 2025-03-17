<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response {
        $excludedRoutes = [
            'api/web/login', 
            'api/web/register'
        ];
        if(in_array($request->path(), $excludedRoutes)) {
            return $next($request);
        }

        $JWTAuth = JWTAuth::parseToken()->authenticate();  
        $userId = $JWTAuth->id;  
        $user = User::with([
                'userDetail', 
                'userUserType',
                'userUserType.userType',
            ])
            ->where('id', $userId)
            ->first();

        if(!$user) throw new Exception("ID of implementing user not found.");

        $userInstance = [
            'id' => $user->id,
            'username' => $user->username,
            'is_active' => $user->is_active,
            'first_name' => $user->userDetail->first_name,
            'user_middle_name' => $user->userDetail->middle_name,
            'user_last_name' => $user->userDetail->last_name,
            'user_email' => $user->userDetail->email,
            'user_phone' => $user->userDetail->phone,
            'address' => $user->userDetail->address,
            'country' => $user->userDetail->country,
            'date_of_birth' => $user->userDetail->date_of_birth,
            'age' => $user->userDetail->age,
            'gender' => $user->userDetail->gender,
            'profile_image' => $user->userDetail->profile_image,
            'user_type_name' => $user->userUserType->userType->user_type_name,
            'user_type_description' => $user->userUserType->userType->user_type_description,
            'user_type_icon' => $user->userUserType->userType->user_type_icon,
            'user_type_color' => $user->userUserType->userType->user_type_color,
            'user_hierarchy_level' => $user->userUserType->userType->hierarchy_level,
            'positioned_assigned_at' => $user->userUserType->created_at,
            'positioned_modified_at' => $user->userUserType->updated_at,
        ];

        app()->instance(AuthUserService::class, new AuthUserService($userInstance));

        return $next($request);
    }
}
