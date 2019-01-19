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
        $this->activate_code = md5(rand(0,1000));

        $foundedUser = User::where('username', $username)->first();

        if(!is_null($foundedUser)){
            throw new UserAlreadyExistsException("The username is already in use.");
        }
        
        $foundedEmail = User::where('email', $email)->first();
    
        if(!is_null($foundedEmail)){
            throw new EmailAlreadyExistsException("The email is already in use.");
        }
                
        $this->save();

        $activateAccountUrl = "http://localhost:8000/users/" . $username . "/" . $this->activate_code;
        $this->sendEmail($this->email,"Verificar cuenta", "Ingresa a al siguiente link para activar tu cuenta :
             ".$activateAccountUrl);
    }

    private function sendEmail($addressee, $subject, $message)
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
        var_dump($response);
        exit();
        return $response;
    }
}

