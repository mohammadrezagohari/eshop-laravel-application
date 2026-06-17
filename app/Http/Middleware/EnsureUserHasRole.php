<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as HTTPResponse;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        $user = $request->user();

        if (!$user || !$user->hasRole($roles)) {
            return response()->json([
                'message' => 'Forbidden.',
            ], HTTPResponse::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
