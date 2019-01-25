<?php

namespace Atenas\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use Atenas\Models\User\User;
use Atenas\Controllers\Base\Controller;
use Atenas\Models\User\EmailAlreadyExistsException;

class ChangePasswordUserController extends Controller
{
    public function changePassword(Request $request, Response $response)
    {
        $authenticatedUser=$request->getAttribute('username');
        var_dump($authenticatedUser);
        die();
        $body = $request->getParsedBody();


        $errors=array();
        
        $password = filter_var($body['password'], FILTER_SANITIZE_STRING);
        
        if(is_null($password)){
            $errors [] = 
            [  
                'code' => 1020,
                'error' => 'The password can not come null.'
            ];
        }
        if(!empty($errors)){
            $response->withJson(
                [
                    'errors' => $errors
                ],
                400);
        }

        try {
            
                $foundedUser = User::find($authenticatedUser['id']);

                $foundedUser->changePassword($password);

                $foundedUser->save();

            } catch (ChangePasswordUserException $exception) {
                $response->withJson([
                    'error' => [
                        'code'=> 1021,
                        'message' => $exception->getMessage()
                        ]
                ],400);
        }
    

    }
}


?>