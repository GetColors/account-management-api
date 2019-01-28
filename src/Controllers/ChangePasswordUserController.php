<?php

namespace Atenas\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use Atenas\Models\User\User;
use Illuminate\Database\QueryException;
use Atenas\Controllers\Base\Controller;
use Atenas\Models\User\DatabaseException;

class ChangePasswordUserController extends Controller
{
    public function changePassword(Request $request, Response $response)
    {
        $authenticatedUser = $request->getAttribute('username');

        $requestBody = $request->getParsedBody();


        $errors=array();
        
        $password = filter_var($requestBody['password'], FILTER_SANITIZE_STRING);
        
        if(is_null($password)){
            $errors [] = 
            [  
                'code' => 1000,
                'error' => 'The password field can not come null.'
            ];
        }

        if(!empty($errors)){
            $response->withJson(
                [
                    'errors' => $errors
                ],
                400);
        }

        try{
            $foundedUser = User::find($authenticatedUser['id']);
        }catch (QueryException $exception){
            return $response->withJson([
                "errors" => [
                    "code" => 1011,
                    "message" => "User was not found."
                ]
            ], 400);
        }

        try {
            $foundedUser->changePassword($password);
        } catch (DatabaseException $exception) {
                $response->withJson([
                    "error" => [
                        "code" => 1021,
                        "message" => "Something was wrong."
                        ]
                ],400);
        }
    }
}


?>