<?php

namespace Atenas\Middlewares;

class TokenGeneratorMiddleware
{
    public function __invoke(Request $request, Response $response, $next)
    {
        

        $response = $next($request, $response);
        $result = json_decode($response->getBody());
        if(isset($result->errors)){
            return $response;
        }
        $response->withJson([
            'data' => TokenGenerator::generate($result->data)
        ]);
        return $response;
    }
}

