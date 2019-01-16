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

    public function register(string $username, string $email, string $password):void
    {
        $this->username = $username;
        $this->email = $email;
        $this->password = password_hash($password, PASSWORD_BCRYPT);

        try{
            $this->save();
        }catch (QueryException $exception){
            throw $exception;
        }
    }
}