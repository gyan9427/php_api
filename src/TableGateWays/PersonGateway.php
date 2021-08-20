<?php
namespace Src\TableGateWays;

class PersonGateway{
    private $db=null;

    public function __construct($db){

        $this->db = $db;

    }

    public function findAll(){
        $statement = "
        SELECT * FROM person;
        ";

        try{
            $statement = $this->db->query($statement);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        }catch(\PDOException $e){
            exit($e->getMessage());
        }
    }

    public function find($id){
        $statement = "
        SELECT * FROM person WHERE id = ?
        ";

        try{
            $statement = $this->db->prepare($statement);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        }catch(\PDOException $e){
            exit($e->getMessage());
        }
    }
    
    public function insert(Array $arr){
        $statement = "
        INSERT INTO person (firstname, lastname, firstparent_id, secondparent_id) VALUES (:firstname, :lastname, :firstparent_id, :secondparent_id)
        ";
        try{
        $statement =  $this->db->prepare($statement);
        $statement->execute(
           Array ('firstname' => $input['firstname'],
                'lastname'  => $input['lastname'],
                'firstparent_id' => $input['firstparent_id'] ?? null,
                'secondparent_id' => $input['secondparent_id'] ?? null,
            ));

        return $statement->rowCount();
        }catch(\PDOException $e){
            exit($e->getMessage());
        }
    }

    public function delete($id){
        $statement = "
        DELETE * FROM person WHERE id = ?
        ";

        try{
            $statement = $this->db->prepare($statement);

            $statement->execute(Array('id'=>$id));

            return $statement->rowCount();

        }catch(\PDOException $e){
            exit($e->Message());
        }
    } 

    public function update(Array $arr){
        $statement = "
        UPDATE person SET (
            firstname = :firstname, lastname = :lastname, firstparent_id =:firstparent_id, secondparent_id = :secondparent_id
            )";

        try{

            $statement = $this->db->prepare($statement);
            
            $statement->execute(Array(
                'firstname'=> $input['firstname'],
                'lastname'=>$input['lastname'],
                'firstparent_id'=>$input['firstparent_id']??null,
                'secondparent_id'=>$input['secondparent_id']??null
            ));
            return $statement->rowCount();           
        }catch(\PDOException $e){
            exit($e->getMessage());
        }
        
    }
}