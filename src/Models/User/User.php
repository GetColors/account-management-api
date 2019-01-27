<?php

namespace Atenas\Models\User;

use Illuminate\Database\Eloquent\Model;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Connection\AMQPSSLConnection;
use PhpAmqpLib\Message\AMQPMessage;
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
        $this->activation_code = mt_rand(10000,99999);

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

    private function sendEmail(string $addressee,string  $subject,string  $message):void
    {
        $data = array(
            "addressee" => $addressee,
            "subject" => $subject,
            "message" => $message
        );

        $url = parse_url(getenv('CLOUDAMQP_URL'));
        $vhost = substr($url['path'], 1);
        if($url['scheme'] === "amqps") {
            $ssl_opts = array(
                'capath' => '/etc/ssl/certs'
            );
            $conn = new AMQPSSLConnection($url['host'], 5671, $url['user'], $url['pass'], $vhost, $ssl_opts);
        } else {
            $conn = new AMQPStreamConnection($url['host'], 5672, $url['user'], $url['pass'], $vhost);
        }
        $ch = $conn->channel();
        $exchange = 'amq.direct';
        $queue = 'send_email';
        $ch->queue_declare($queue, false, true, false, false);
        $ch->exchange_declare($exchange, 'direct', true, true, false);
        $ch->queue_bind($queue, $exchange);

        $msg = new AMQPMessage(json_encode($data), array('content_type' => 'text/plain', 'delivery_mode' => 2));
        $ch->basic_publish($msg, $exchange);

    }

    public function changePassword(string $password):void
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
        
    }
}

