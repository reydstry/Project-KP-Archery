<?php

namespace App\Http\Middleware;

use App\Enums\UserRoles;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated.',
            ], 401);
        }

        // Check if user role is in allowed roles
        if (!in_array($user->role->value, $roles)) {
            return response()->json([
                'message' => 'Forbidden. Anda tidak memiliki akses.',
            ], 403);
        }

        return $next($request);
    }
}