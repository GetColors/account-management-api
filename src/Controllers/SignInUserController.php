<?php

namespace Atenas\Controllers;

use Atenas\Controllers\SignInException\SignInException;
use Slim\Http\Request;
use Slim\Http\Response;
use Atenas\Models\User\User;
use Atenas\Controllers\Base\Controller;

class SignInUserController extends Controller
{
    public function login(Request $request, Response $response)
    {
        $user = $request->getParsedBody();
        $username = $user['username'];
        $password = $user['password'];

        $errors=array();



        if(is_null($username) || is_null($password)){
            $errors [] = ['code'=>1000,'error'=>'The fields must not be null.'];
        }
        if(!empty($errors)){
            $response->withJson(
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

        $foundedUser = User::where('username',$username)->first();

        if($foundedUser==NULL){
            $message [] = [
                'code'=>1007,
                'description'=>'Username not found.'
            ];
            json_encode($message);
        }
        try {
            if($username==$foundedUser->username && password_verify($password, $foundedUser->password)){
                $response->withJson(
                    [
                        'data' => [
                            "username" => $foundedUser->username,
                            "email" => $foundedUser->email
                        ]
                    ],
                    200);
            }else{
                $response->withJson(
                    [
                        'error' => "Username or password not valid. "
                    ],
                    400);
            }
        } catch (SignInException $exception) {
            $response->withJson([
                'error' => [
                    'code'=> 1010,
                    'message' => $exception->getMessage()
                ]
            ],400);
        }
    }
}