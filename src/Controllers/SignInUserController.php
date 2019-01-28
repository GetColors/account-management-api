<?php

namespace Atenas\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use Atenas\Models\User\User;
use Atenas\Controllers\Base\Controller;
use Atenas\Models\User\DatabaseException;
use Atenas\Models\User\InvalidCredentialsException;

class SignInUserController extends Controller
{
    public function login(Request $request, Response $response)
    {
        $requestBody = $request->getParsedBody();

        $username = $requestBody['username'];
        $password = $requestBody['password'];

        $errors=array();

        if(is_null($username)){
            $errors [] = ['code'=>1000,'error'=>'The username field must not be null.'];
        }

        if(is_null($password)){
            $errors [] = ['code'=>1000,'error'=>'The password field must not be null.'];
        }

        if(!empty($errors)){
            return $response->withJson(
                [
                    'errors' => $errors
                ],
                400);
        }

        if(strlen($username)>15 || strlen($username)<5){
            $errors [] = ['code'=>1004,'error'=>'The username can not be less than 5, nor more than 15 characters.'];
        }

        if(empty($username)){
            $errors [] = ['code'=>1001,'error'=>'The user field can not remain empty.'];
        }
        if(empty($password)){
            $errors [] = ['code'=>1001,'error'=>'The user field can not remain empty.'];
        }
        if(strlen($password)<8){
            $errors [] = ['code'=>1004,'error'=>'The password can not be less than 5, nor more than 15 characters.'];
        }

        if(!empty($errors)){
            return $response->withJson(
                [
                    'errors' => $errors
                ],
                400);
        }

        $user = new User();

        try {
            $user->signin($username, $password);
        } catch (InvalidCredentialsException $e) {
            return $response->withJson([
                "errors" => [
                    "code" => 1013,
                    "message" => "Given username or password are incorrect."
                ]
            ],400);
        } catch (DatabaseException $e) {
            return $response->withJson([
                "errors" => [
                    "code" => 1012,
                    "message" => "Something was wrong."
                ]
            ],400);
        }
    }
}