<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Firebase\JWT\JWT;

class JwtMiddleware
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
        $token = $request->header('authorization');

        if(!$token)
        {
            return response()->json([
                'data' => [],
                'message' => 'Token de acesso deve ser fornecido'
            ]);
        }

        try
        {
            $credentials = JWT::decode($token,env('JWT_SECRET'),['HS256']);
        }
        catch (\Exception $exception)
        {
            return response()->json([
                'data' => [],
                'message' => $exception->getMessage()
            ]);
        }

        $user = User::find($credentials->sub);

        $request->auth = $user;

        return $next($request);
    }
}
