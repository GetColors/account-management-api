<?php

namespace Atenas\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use Atenas\Models\User\User;
use Atenas\Controllers\Base\Controller;

class RegisterUserController extends Controller
{
    public function register(Request $request, Response $response)
    {
        $body = $request->getParsedBody();

        $username = $body['username'];
        $email = $body['email'];
        $password = $body['password'];

        User::create(
            [
                $username,
                $email,
                $password
            ]
        );

        $response->withJson(
            [
                'data' => "usuario registrado"
            ],
            200);
    }
}