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
        SELECT id,firstname,lastname FROM person WHERE id = ?;
        ";

        try{
            $statement = $this->db->prepare($statement);
            $statement->execute(array($id));
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        }catch(\PDOException $e){
            exit($e->getMessage());
        }
    }
    
    public function insert(Array $input){
        $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
         
        $statement = "
        INSERT INTO person(firstname, lastname, firstparent_id, secondparent_id) VALUES (:firstname, :lastname, :firstparent_id, :secondparent_id)
        ";
        try{
        $statement =  $this->db->prepare($statement);
        $statement->execute(
           Array ('firstname' => $input['firstname'],
                'lastname'  => $input['lastname'],
                'firstparent_id' => $input['firstparent_id'] ?? null,
                'secondparent_id' => $input['secondparent_id'] ?? null,
            ));

        $this->db->lastInsertId();

        return $statement->rowCount();
        }catch(\PDOException $e){
            exit($e->getMessage());
        }
    }

    public function delete($id){
    
        $statement = "
        DELETE FROM person WHERE id = :id;
        ";

        try{
            $statement = $this->db->prepare($statement);
            //bind parameter correction
            $statement->bindParam(':publisher_id', $id, \PDO::PARAM_INT);
            $statement->execute(Array('id'=>$id));

            return $statement->rowCount();

        }catch(\PDOException $e){
            exit($e->Message());
        }
    } 

    public function update($id,Array $input){
        $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        //correction made in statement - removed ()
        $statement = "
        UPDATE person SET 
            firstname = :firstname, lastname = :lastname, firstparent_id =:firstparent_id, secondparent_id = :secondparent_id
             WHERE id = :id ;";

        try{

            $statement = $this->db->prepare($statement);
            //correction id in the array statement id added
            $statement->execute(Array(
                'id'=> (int) $id,
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