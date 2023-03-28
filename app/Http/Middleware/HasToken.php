<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\UserToken;

class HasToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (empty($token)) {
            return $this->sendFailedResponse();
        }

        $userToken = UserToken::getByToken($token);

        if (empty($userToken)) {
            return $this->sendFailedResponse();
        }

        if ($userToken->expired_at < time()) {
            UserToken::deleteByUserId($userToken->user_id);
            
            return $this->sendFailedResponse();
        }

        $request->attributes->add(['user_id' => $userToken->user_id]);

        return $next($request);
    }

    private function sendFailedResponse()
    {
        return response()->json([
            'status' => false
        ], 401);
    }
}
