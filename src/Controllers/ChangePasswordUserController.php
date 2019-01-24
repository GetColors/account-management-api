<?php

namespace Atenas\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use Atenas\Models\User\User;
use Atenas\Controllers\Base\Controller;
use Atenas\Models\User\EmailAlreadyExistsException;

class ChangePasswordUserController extends Controller
{
    public function changePassword(Request $request, Response $response, $args)
    {

        $password = $args['password'];
        $errors=array();
        // Descencriptar antes!!
        
        $password = filter_var($password, FILTER_SANITIZE_STRING);
        
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
            
            $foundedPassword = User::where('password',$password)->first();
            
            if(is_null($foundedPassword)){
                $errors [] = 
                [  
                    'code' => 1020,
                    'error' => 'The variable foundedPassword it is empty.'
                ];
            }
            if(!empty($errors)){
                $response->withJson(
                    [
                        'errors' => $errors
                    ],
                    400);
            }

            if($foundedPassword===$password){
                $this->password=$password;
                $response->withJson(
                    [
                        'message' => 'The password has been updated.'
                    ],
                        200);
            }


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