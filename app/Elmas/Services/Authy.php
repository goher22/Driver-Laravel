<?php

namespace App\Elmas\Services;

class Authy
{

    /**
     * @var \Authy\AuthyApi
     */
    private $api;

    public function __construct()
    {
        $this->api = new \Authy\AuthyApi(setting('auth.two_factor_api_key'));
    }

    /**
     * @param $email
     * @param $phone_number
     * @param $country_code
     * @return int
     * @throws \Exception
     */
    function register($email, $phone_number, $country_code)
    {
        $user = $this->api->registerUser($email, $phone_number, $country_code);

        return $user;
    }

    /**
     * @param $authy_id
     * @return bool
     * @throws \Exception
     */
    public function sendToken($authy_id)
    {
        $response = $this->api->requestSms($authy_id);
        
        return $response;
    }

    /**
     * @param $authy_id
     * @param $token
     * @return bool
     * @throws \Exception Nothing will be thrown here
     */
    public function verifyToken($authy_id, $token)
    {
        $response = $this->api->verifyToken($authy_id, $token);

        return $response;
    }

    /**
     * @param $authy_id
     * @return \Authy\value status
     * @throws \Exception if request to api fails
     */
    public function verifyUserStatus($authy_id) {
        $response = $this->api->userStatus($authy_id);

        return $response;
    }
}
