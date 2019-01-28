<?php

namespace Atenas\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

class User extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'username',
        'email',
        'password'
    ];


    /**
     * @param string $username
     * @param string $email
     * @param string $password
     * @throws DatabaseException
     * @throws EmailAlreadyExistsException
     * @throws UserAlreadyExistsException
     */
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

        try{
            $this->save();
        }catch (QueryException $exception){
            throw new DatabaseException($exception->getMessage());
        }
    }


    public function changePassword(string $password):void
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
        $this->save();
    }
}

