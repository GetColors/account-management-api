<?php

namespace Atenas\Controllers;

use Atenas\Models\User\DatabaseException;
use Slim\Http\Request;
use Slim\Http\Response;
use Atenas\Controllers\Base\Controller;
use Atenas\UseCases\RegisterUserUseCase;
use Atenas\Models\User\UserAlreadyExistsException;
use Atenas\Models\User\EmailAlreadyExistsException;
use Atenas\UseCases\Exceptions\InvalidParametersException;

class RegisterUserController extends Controller
{
 
    public function register(Request $request, Response $response)
    {
        $body = $request->getParsedBody();
        $errors=array();

        $username = filter_var($body['username'], FILTER_SANITIZE_STRING);
        $email = filter_var($body['email'], FILTER_SANITIZE_STRING);
        $password = filter_var($body['password'], FILTER_SANITIZE_STRING);

        if(is_null($username))
        {
            $errors [] = ['code'=>1000,'error'=>'The username field must not be null.'];
        }

        if(is_null($email))
        {
            $errors [] = ['code'=>1000,'error'=>'The email field must not be null.'];
        }

        if(is_null($password))
        {
            $errors [] = ['code'=>1000,'error'=>'The password field must not be null.'];
        }

        if(!empty($errors)){
            $response->withJson(
                [
                    'errors' => $errors
                ],
                400);
        }

        $useCase = new RegisterUserUseCase();


        try{

            $useCase->execute($username, $email, $password);

            $response->withJson(
                [
                    "message" => "Has been registered successfully, an account activation is required."
                ],
                200);

        } catch (InvalidParametersException $exception) {

            $response->withJson(
                [
                    'errors' => $exception->getErrors()
                ],
                400);
        } catch (UserAlreadyExistsException $exception){

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
        } catch (DatabaseException $e) {
            $response->withJson([
                'error' => [
                    'code'=> 1009,
                    'message' => "Something was wrong."
                ]
            ],400);
        }
    }
}