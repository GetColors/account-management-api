<?php

namespace Atenas\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use Atenas\Controllers\Base\Controller;

class ActivateUserController extends Controller
{
    public function activate(Request $request, Response $response)
    {
        $requestBody = $request->getParsedBody();

        $username = $requestBody['username'];
        $code = $requestBody['code'];

        if(is_null($username)){
            $errors [] = ['code'=>1000,'error'=>'The username field must not be null.'];
        }

        if(is_null($code)){
            $errors [] = ['code'=>1000,'error'=>'The code field must not be null.'];
        }

        if(!empty($errors)){
            $response->withJson(
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
            $response->withJson(
                [
                    'errors' => $errors
                ],
                400);
        }

        $foundedUser = User::where('username',$username)->first();

        if(is_null($foundedUser)){
            print_r("User not found.");
            exit(400);
        }

        if($foundedUser->activate_code !== $code){
            print_r("Validation code invalid.");
            exit(400);
        }

        $foundedUser->activate_code = 1;
        $foundedUser->save();
        $response->withJson(
            [
                'message' => $username . ' your account has been activated.'
            ],
            200);
    }
}