<?php

include_once dirname(__FILE__) . '/Exceptions.php';
include_once dirname(__FILE__) . '/ResponseStatus.php';
include_once dirname(__FILE__) . '/RequestMethod.php';

class Authenticator
{
    var $data;
    var $config;
    var $basic;

    function renewToken()
    {
        if($this->getAccessToken()==null){
            $this->createNewtoken();
            return;
        }		
        $url = $this->config->base_url."/applicationmgt/authenticate";
        $r = getHTTP($url, null, RequestMethod::GET, null,array("Accept: application/json", "X-Secret: " . $this->config->auth_consumerSecret), null, true);

        if ($r['status'] != ResponseStatus::OK)
            throw new  ConnectionException($r['msg']);

        if ($r['statusCode'] == 400 && strpos($r['body'], "invalid_grant") !== false) {
            $this->createNewtoken();
            return;
        } else if ($r['statusCode'] != 200)
            throw new  AuthenticationException("Wrong Access Token");

        $body = json_decode($r['body']);
        $this->data->accessToken 	= 	$body->access_token;
        $this->data->scope 			= 	$body->scope;
        $this->data->token_type 	= 	$body->token_type;
        $this->data->expire 		= 	$body->expires_in;

        file_put_contents(dirname(__FILE__) . "/data.json", json_encode($this->data, JSON_PRETTY_PRINT));
        file_put_contents(dirname(__FILE__) . "/../config.json", json_encode($this->config, JSON_PRETTY_PRINT));
    }

    function __construct()
    {
        include dirname(__FILE__) ."/curl.php";
        $this->data = json_decode(file_get_contents(dirname(__FILE__) . "/data.json"));
        $this->config = json_decode(file_get_contents(dirname(__FILE__) . "/../config.json"));
        //echo (file_get_contents(dirname(__FILE__) . "/config.json"));
        //var_dump($this->data);
        $this->basic = $this->config->auth_consumerSecret;
    }

    function getAccessToken()
    {
        if(!isset($this->data) || !isset($this->data->accessToken)){
            return null;
        }
        return $this->data->accessToken;
    }

    function createNewtoken()
    {
        if (isset($this->config->auth_username) == false || $this->config->auth_username == null || strlen($this->config->auth_username) <= 1 || $this->config->auth_pw == false || $this->config->auth_pw == null || strlen($this->config->auth_pw) <= 1) {
            throw new  AuthenticationException("Wrong Access Token. Please recreate one");
        }
        $url = $this->config->base_url."/applicationmgt/authenticate";
        $r = getHTTP($url,null, RequestMethod::GET, null,array("accept: application/json","X-Secret: ". $this->config->auth_consumerSecret), null, true);

        if ($r['statusCode'] != 200)
            throw new  AuthenticationException("Failed to create access token");
        $body = json_decode($r['body']);

        if($this->data == null )
            $this->data = new stdClass();

        $this->data->accessToken	= $body->access_token;
        $this->data->scope 			= $body->scope;
        $this->data->token_type 	= $body->token_type;
        $this->data->expire 		= $body->expires_in;
        //file_put_contents(dirname(__FILE__) . "/data.json", json_encode($this->data, JSON_PRETTY_PRINT));
        $this->createXtoken();
    }

    function createXtoken()
    {
        //$url = $this->config->user_auth_url;
		$url = $this->config->base_url."/usermgt/v1/authenticate";

        $body= '{ "username": "'.$this->config->auth_username.'", "password": "'.$this->config->auth_pw.'"}';
        $r = getHTTP($url,$body, RequestMethod::POST, null,array("accept: application/json","Authorization:Bearer ".$this->getAccessToken(),"content-type:application/json"), null, true);
		//var_dump($r);

        if ($r['statusCode'] == 401 && strpos($r['body'], 'Invalid Credentials') !== false) {
            $this->renewToken();
            $r = getHTTP($url,$body, RequestMethod::POST, null,array("accept: application/json","Authorization:Bearer ".$this->getAccessToken(),"content-type:application/json"), null, true);
        }

        if ($r['statusCode'] != 200)
            throw new  AuthenticationException("Failed to create X-IoT-JWT token");
        $body = json_decode($r['body']);
        $this->data->xToken = $body->{'X-IoT-JWT'};
        file_put_contents(dirname(__FILE__) . "/data.json", json_encode($this->data, JSON_PRETTY_PRINT));
        return $this->data->xToken;
    }

    function getXtoken(){
        if(!isset($this->data) || !isset($this->data->xToken)){
            $this->createNewtoken();
        }
        return $this->data->xToken;
    }

}