<?php
include dirname(__FILE__) . '/lib/Exceptions.php';
include dirname(__FILE__) . '/lib/Authenticator.php';
class DialogIoTAPIHandler
{
    var $auth;
    function __construct()
    {
        $this->auth = new Authenticator();
    }

    function sendAPICall($url, $method, $body, $contentType = "application/json", $accept = "application/json")
    {
        if($this->auth->getAccessToken() == null){
            $this->auth->renewToken();
        }
        $r = getHTTP($url, $body, $method, null, array("Accept: " . $accept, "Authorization: Bearer ".$this->auth->getAccessToken(),"X-IoT-JWT: ".$this->auth->getXtoken()), null, true);
       // var_dump($r) ;
        if ($r['statusCode'] == 401 || strpos($r['body'], 'Expired' ) !== false || strpos($r['body'], 'Access Denied' ) !== false ) {
            $this->auth->createNewtoken();
            $r = getHTTP($url, $body, $method, null, array("Accept: " . $accept, "Authorization: Bearer " . $this->auth->getAccessToken(),"X-IoT-JWT: ".$this->auth->getXtoken()), null, true);
        }	
        return $r;
    }
}