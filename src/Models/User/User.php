<?php

namespace Atenas\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
include 'EmailAlreadyExistsException.php';
include 'UserAlreadyExistsException.php';

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
            
        
        $foundedUser = User::where('username', $username)->first();
        $foundedEmail = User::where('email', $email)->first();
        
        if(!is_null($foundedUser)){
            throw new UserAlreadyExistsException();
        }
        if(!is_null($foundedEmail)){
            throw new EmailAlreadyExistsException();
        }        
        $this->save();
    }
}

