<?php

namespace Atenas\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use Atenas\Models\User\User;
use Atenas\Controllers\Base\Controller;
use Atenas\Models\User\UserAlreadyExistsException;
use Atenas\Models\User\EmailAlreadyExistsException;

class RegisterUserController extends Controller
{ 
    public function activate(Request $request, Response $response, $args)
    {
        $username = $args['username'];
        $code = $args['code'];

        if(is_null($username) || is_null($code)){
            $errors [] = ['code'=>1000,'error'=>'The fields must not be null.'];
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
 
    public function register(Request $request, Response $response)
    {
        $body = $request->getParsedBody();
        $errors=array();

        $username = filter_var($body['username'], FILTER_SANITIZE_STRING);
        $email = filter_var($body['email'], FILTER_SANITIZE_STRING);
        $password = filter_var($body['password'], FILTER_SANITIZE_STRING);


        
        if(is_null($username) || is_null($email) || is_null($password))
        {
            $errors [] = ['code'=>1000,'error'=>'The fields must not be null.'];
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
        if(empty($email)){
            $errors [] = ['code'=>1002,'error'=>'The mail field can not remain empty.'];
        }
        if(empty($password)){
            $errors [] = ['code'=>1003,'error'=>'The password field can not remain empty.'];
        }

        if(strlen($username)>15 || strlen($username)<5){
            $errors [] = ['code'=>1004,'error'=>'The username can not be less than 5, nor more than 15 characters.'];
        }
        if(strlen($password)<8){
            $errors [] = ['code'=>1005,'error'=>'The password must be greater than 5 characters.'];
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $errors [] = ['code'=>1006,'error'=>'You must enter a valid email.'];
        }

        if(!empty($errors)){
            $response->withJson(
                [
                    'errors' => $errors
                ],
                400);
        }
        
        $user= new User();

        try{
            $user->register($username, $email, $password);

            $response->withJson(
                [
                    "message" => "Has been registered successfully, an account activation is required."
                ],
                200);
        }catch (UserAlreadyExistsException $exception){
            
            $response->withJson([
                'error' => [
                    'code'=> 1007,
                    'message' => $exception->getMessage()
                    ]
            ],400);
        } catch(EmailAlreadyExistsException $exception){
            $response->withJson([
                'error' => [
                    'code'=> 1008,
                    'message' => $exception->getMessage()
                    ]
            ],400);
        }
    }
}