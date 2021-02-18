<?php

namespace App\Http\Middleware;

use App\Helpers\APIHelper;
use Closure;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class JWT
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            JWTAuth::parseToken()->authenticate();
        } catch (TokenExpiredException $e) {
            return APIHelper::makeAPIResponse(false,'Token Expired',null,401);
        } catch (TokenInvalidException $e) {
            return APIHelper::makeAPIResponse(false,'Invalid Token',null,401);
        } catch (JWTException $e) {
            return APIHelper::makeAPIResponse(false,'Token Exception',null,401);
        } catch (\Exception $e) {
            return APIHelper::makeAPIResponse(false,'Token Exception',null,400);
        }
        return $next($request);
    }
}
