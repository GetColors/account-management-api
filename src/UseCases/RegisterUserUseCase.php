<?php

namespace Atenas\UseCases;

use Atenas\Models\Email\EmailSender;
use Atenas\Models\User\User;
use Atenas\UseCases\Exceptions\InvalidParametersException;

class RegisterUserUseCase
{
    /**
     * @param string $username
     * @param string $email
     * @param string $password
     * @throws InvalidParametersException
     * @throws \Atenas\Models\User\DatabaseException
     * @throws \Atenas\Models\User\EmailAlreadyExistsException
     * @throws \Atenas\Models\User\UserAlreadyExistsException
     */
    public function execute(string $username, string $email, string $password):void
    {
        if(empty($username)){
            $errors [] = ['code'=>1001,'error'=>'The username field can not remain empty.'];
        }
        if(empty($email)){
            $errors [] = ['code'=>1001,'error'=>'The email field can not remain empty.'];
        }
        if(empty($password)){
            $errors [] = ['code'=>1001,'error'=>'The password field can not remain empty.'];
        }

        if(strlen($username)>15 || strlen($username)<5){
            $errors [] = ['code'=>1002,'error'=>'The username can not be less than 5, nor more than 15 characters.'];
        }
        if(strlen($password)<8){
            $errors [] = ['code'=>1003,'error'=>'The password must be greater than 5 characters.'];
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $errors [] = ['code'=>1004,'error'=>'You must enter a valid email.'];
        }

        if(!empty($errors)){
            throw new InvalidParametersException($errors);
        }

        $user= new User();


        $user->register($username, $email, $password);

        $subject = "Verifica tu cuenta";
        $activationCode = $user->activation_code;

        $message = "Bienvenido " . $username . ", estás a un paso de terminar tu registro, para finalizar solo debes ingresar el siguiente código en la web: 
        " . $activationCode;


        $emailSender = new EmailSender();
        $emailSender->sendEmail($email, $subject, $message);
    }
}