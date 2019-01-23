<?php

namespace Atenas\Common;

use Firebase\JWT\JWT;
class TokenGenerator
{
    private $secretKey;
    private $tokenDurationInMinutes = 30;
    private $hashType = 'HS256';

    protected function __construct()
    {
        $this->secretKey = API_CONFIG['secretKey'];
    }

    public static function generate($data):array
    {
        $tokenGenerator = new TokenGenerator();
        return $tokenGenerator->trueGenerate($data);
    }
    private function trueGenerate($data):array
    {
        return [
            'token' => JWT::encode($this->generatePayload($data), $this->secretKey),
            'duration' => 60*$this->tokenDurationInMinutes,
            'type' => 'Bearer'
        ];
    }
    private function generatePayload($data):array
    {
        return [
            'iss' => API_CONFIG['host'],
            'iat' => time(),
            'exp' => time() + (60*$this->tokenDurationInMinutes),
            'user' => $data
        ];
    }
    public static function getPayload(string $token)
    {
        $tokenGenerator = new TokenGenerator();
        return $tokenGenerator->trueGetPayload($token);
    }
    private function trueGetPayload(string $token)
    {
        return JWT::decode($token, $this->secretKey, [$this->hashType]);
    }
}