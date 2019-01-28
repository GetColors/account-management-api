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


    /**
     * @param string $username
     * @param string $activationCode
     * @throws InvalidActivationCode
     * @throws UserDoesNotExistsException
     * @throws DatabaseException
     */
    public function activate(string $username, string $activationCode):void
    {
        try{
            $foundedUser = User::where('username',$username)->first();
        }catch (QueryException $exception){
            throw new DatabaseException();
        }

        if(is_null($foundedUser)){
            throw new UserDoesNotExistsException();
        }

        if($foundedUser->activation_code !== $activationCode){
            throw new InvalidActivationCode();
        }

        $foundedUser->status = 1;
        try{
            $foundedUser->save();
        }catch (QueryException $exception){
            throw new DatabaseException();
        }
    }

    /**
     * @param string $username
     * @param string $password
     * @return User
     * @throws DatabaseException
     * @throws InvalidCredentialsException
     */
    public function signin(string $username, string $password):User
    {
        try{
            $foundedUser = User::where('username',$username)->first();
        }catch (QueryException $exception){
            throw new DatabaseException();
        }

        if(is_null($foundedUser)){

            throw new InvalidCredentialsException();
        }

        if(!password_verify($password, $foundedUser->password)) {
            throw new InvalidCredentialsException();
        }

        return $foundedUser;
    }


    public function changePassword(string $password):void
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
        $this->save();
    }
}

