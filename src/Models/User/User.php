<?php

namespace Atenas\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Atenas\Models\User\UserAlreadyExistsException;
use Atenas\Models\User\EmailAlreadyExistsException;

class User extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'username',
        'email',
        'password'
    ];


    public function register(string $username, string $email, string $password):void
    {
        $this->username = $username;
        $this->email = $email;
        $this->password = password_hash($password, PASSWORD_BCRYPT);
        $this->activate_code = mt_rand(10000,99999);

        $foundedUser = User::where('username', $username)->first();

        if(!is_null($foundedUser)){
            throw new UserAlreadyExistsException("The username is already in use.");
        }
        
        $foundedEmail = User::where('email', $email)->first();
    
        if(!is_null($foundedEmail)){
            throw new EmailAlreadyExistsException("The email is already in use.");
        }
                
        $this->save();

        $subject = "Verifica tu cuenta";
        $activationCode = $this->activate_code;
        $message = "Bienvenido " . $username . ", estás a un paso de terminar tu registro, para finalizar solo debes ingresar el siguiente código en la web: 
        " . $activationCode;

        $this->sendEmail($email,$subject, $message);
    }

    private function sendEmail($addressee, $subject, $message):void
    {
        $data = array(
            "addressee" => $addressee,
            "subject" => $subject,
            "message" => $message
        );

        $curl = curl_init();
        $payload = json_encode($data);
		curl_setopt($curl, CURLOPT_URL, 'https://getcolors-notifications-api.herokuapp.com/email');
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
        curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
		$response = json_decode(curl_exec($curl));
    }
}

