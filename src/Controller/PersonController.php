<?php
namespace Src\Controller;

use Src\TableGateways\PersonGateway;

class PersonController{
    private $db;
    private $requestMethod;
    private $userId;

    private $personGateway;

    public function __construct($db,$requestMethod,$userId){
        $this->db = $db;
        $this->requestMethod =$requestMethod;
        $this->userId = $userId;

        $this->personGateway = new PersonGateway($db);
    }

    
}
