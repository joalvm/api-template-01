<?php

namespace App\Http\Middleware;

use App\Components\JWT;
use App\Exceptions\Auth\InvalidTokenException;
use App\Exceptions\Auth\TokenExpiredException;
use App\Exceptions\Auth\TokenNotFoundException;
use App\Facades\Session;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Illuminate\Http\Request;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     */
    public function handle($request, \Closure $next)
    {
        $token = $this->decodeBearerToken($request->bearerToken());

        Session::authenticate($token->jti);

        return $next($request);
    }

    private function decodeBearerToken(?string $bearerToken): \stdClass
    {
        if (is_null($bearerToken)) {
            throw new TokenNotFoundException();
        }

        try {
            return JWT::decode($bearerToken);
        } catch (BeforeValidException $ex) {
            throw new InvalidTokenException();
        } catch (ExpiredException $ex) {
            throw new TokenExpiredException();
        } catch (SignatureInvalidException $ex) {
            throw new InvalidTokenException();
        } catch (\UnexpectedValueException $ex) {
            throw new InvalidTokenException();
        }
    }
}
