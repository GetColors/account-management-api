<?php

namespace Atenas\Middlewares;

use Atenas\Common\TokenGenerator;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Slim\Http\Request;
use Slim\Http\Response;
use UnexpectedValueException;

class TokenValidationMiddleware
{
    public function __invoke(Request $request, Response $response, $next)
    {
        $token = null;
        $headers = $request->getHeaderLine('HTTP_AUTHORIZATION');
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                $token = ($matches[1]);
            }else{
                return $response->withJson([
                    'errors' => [
                        'code' => 1049,
                        'message' => 'You should give a Bearer token'
                    ]
                ]);
            }
        }
        try{
            $payload = TokenGenerator::getPayload($token);
        }catch (ExpiredException $exception){
            return $response->withJson([
                'errors' => [
                    'code' => 1050,
                    'message' => 'The authentication token has been expired, please signin to get a valid token.'
                ]
            ], 400);
        }catch (SignatureInvalidException $exception){
            return $response->withJson([
                'errors' => [
                    'code' => 1051,
                    'message' => 'The authentication token signature verification has failed, please signin to get a valid token.'
                ]
            ],400);
        }catch (UnexpectedValueException $exception){
            return $response->withJson([
                'errors' => [
                    'code' => 1052,
                    'message' => 'The provided token is not valid, please signin to get a valid token.'
                ]
            ], 400);
        }
        $request = $request->withAttribute('user', $payload->user);
        $response = $next($request, $response);
        return $response;
    }
    /**
     * get access token from header
     * */
    function getBearerToken() {
        $headers = $this->getAuthorizationHeader();
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }
    /**
     * Get header Authorization
     * */
    function getAuthorizationHeader(){
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        }
        else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }
}