<?php

namespace Atenas\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use Atenas\Models\User\User;
use Atenas\Controllers\Base\Controller;
use Atenas\Models\User\DatabaseException;
use Atenas\Models\User\InvalidActivationCode;
use Atenas\Models\User\UserDoesNotExistsException;

class ActivateUserController extends Controller
{
    public function activate(Request $request, Response $response, $args)
    {
        $requestBody = $request->getParsedBody();

        $username = $args['username'];
        $code = $requestBody['code'];

        if(is_null($username)){
            $errors [] = ['code'=>1000,'error'=>'The username field must not be null.'];
        }

        if(is_null($code)){
            $errors [] = ['code'=>1000,'error'=>'The code field must not be null.'];
        }

        if(!empty($errors)){
            return $response->withJson(
                [
                    'errors' => $errors
                ],
                400);
        }

        if(empty($username)){
            $errors [] = ['code'=>1001,'error'=>'The user field can not remain empty.'];
        }
        if(empty($code)){
            $errors [] = ['code'=>1001,'error'=>'The user field can not remain empty.'];
        }
        if(strlen($username)>15 || strlen($username)<5){
            $errors [] = ['code'=>1004,'error'=>'The username can not be less than 5, nor more than 15 characters.'];
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
            $user->activate($username, $code);

            $response->withJson(
                [
                    'message' => $username . ' your account has been activated.'
                ],
                200);

        } catch (UserDoesNotExistsException $exception) {
            return $response->withJson([
                "errors" => [
                    "code" => 1011,
                    "message" => "User does not exists."
                ]
            ],400);
        } catch (InvalidActivationCode $exception) {
            return $response->withJson([
                "errors" => [
                    "code" => 1012,
                    "message" => "Invalid activation code was given."
                ]
            ],400);
        } catch (DatabaseException $exception) {
            return $response->withJson([
                "errors" => [
                    "code" => 1013,
                    "message" => "Something was wrong."
                ]
            ],400);
        }

    }
}