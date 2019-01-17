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
        // var_dump(User::where("username","jsoto")->get());
        // var_dump(User::find(6));
        $body = $request->getParsedBody();
        $errors=array();

        $username = filter_var($body['username'], FILTER_SANITIZE_STRING);
        $email = filter_var($body['email'], FILTER_SANITIZE_STRING);
        $password = filter_var($body['password'], FILTER_SANITIZE_STRING);


        
        if(is_null($username) || is_null($email) || is_null($password))
        {
            $errors [] = ['code'=>1000,'error'=>'The fields must not be null.'];
        }

        if(!empty($username))
        {
            $errors [] = ['code'=>1001,'error'=>'The user field can not remain empty.'];
        }
        if(!empty($email))
        {
            $errors [] = ['code'=>1002,'error'=>'The mail field can not remain empty.'];
        }
        if(!empty($password))
        {
            $errors [] = ['code'=>1003,'error'=>'The password field can not remain empty.'];
        }

        if(strlen($username)>15 || strlen($username)<5)
        {
            $errors [] = ['code'=>1004,'error'=>'The username can not be less than 5, nor more than 15 characters.'];
        }
        if(strlen($password)<8)
        {
            $errors [] = ['code'=>1005,'error'=>'The password must be greater than 5 characters.'];
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $errors [] = ['code'=>1006,'error'=>'You must enter a valid email.'];
        }

        if(!empty($errors))
        {
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
                    'data' => "usuario registrado"
                ],
                200);
        }catch (QueryException $exception){
            if($exception->code() === 23000){
                $response->withJson([
                    'error' => [
                        'code'=> 23000,
                        'message' => "The user entered is already in use.."
                        ]
                ],400);
            }
            //asi :)
            //por cada uno?
//si, por cada código de error, debes provocarlos eso si.
            //por ejemplo, poniendo mal la pass de la bd
            //enviando emails ya registrados, usuarios
            //asi
        } 
    }
}