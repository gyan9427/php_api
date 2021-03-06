<?php

require "../bootstrap.php";
use Src\Controller\PersonController;

use Src\System\DatabaseConnector;

header("Access-Control-Allow-Origin:*");
header("Content-type:json/application,utf8");
header("Access-Control-Allow-Methods:OPTION,PUT,DELETE,POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH);
$uri = explode('/',$uri);

//enpoints start with /person

if($uri[1] !== 'person'){
    header("HTTP/1.1 404 Not Found");
    exit();
}
//https://developer.okta.com/blog/2019/03/08/simple-rest-api-php
//user ID must be number:

if(isset($uri[2])){
    $userId = (int) $uri[2];
}

$requestMethod = $_SERVER["REQUEST_METHOD"];

//pass the request method and user ID to the PersonController
$dbConnetcion = (new DatabaseConnector())->getConnection();
$controller = new PersonController($dbConnetcion,$requestMethod,$userId);
$controller->processRequest();

function authenticate(){
    try{
        switch(true){
            case array_key_exists('HTTP_AUTHORIZATION',$_SERVER) :
                $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
                break;
            case array_key_exists('Authorization',$_SERVER) :
                $authHeader = $_SERVER['Authorization'];
                break;
            default:
                $authHeader = null;
                break;
        }
        preg_match('/Bearer\s(\S+)/',$authHeader,$matches);
        if(!isset($matches[1])){
            throw new \Exception("No Bearer Token") ;
        }
        $jwtVerifier = (new \Okta\JwtVerifier\JwtVerifierBuilder())
            ->setIssuer(getenv('OKTAISSUER'))
            ->setAudience('api://default')
            ->setClientId(getenv('OKTACLIENTID'))
            ->build();
        return $jwtVerifier->verify($matches[1]);
    } catch (\Exception $e) {
        return false;
    }
}
