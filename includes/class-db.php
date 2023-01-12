<?php

class DB
{
    private $db;

    public function __construct()
    {
        try{
            $this->db = new PDO ('mysql:host=devkinsta_db;dbname=simple_cms',
            'root',
            'cD4FYhCb9HPk9bc0');
        }catch(PDOException $error){
            die('Database connection failed');
        }
    }

    public static function connect()
    {
        return new self(); // equal to = new DB()
        // DB::connect() = new DB()
    }

    /*
    *Trigger select command
    */ 
    public function select($sql,$data=[],$is_list=false)
    {
        $statement=$this->db->prepare($sql);
        $statement->execute($data);

        if($is_list){
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }else{
            return $statement->fetch(PDO::FETCH_ASSOC);
        }

    }

    /*
    *Trigger insert command
    *$sql = insert command
    *$data used in executed()
    */
    public function insert($sql,$data)
    {
        $statement=$this->db->prepare($sql);
        $statement->execute($data);

        return $this->db->lastInsertId();
    }

    /*
    *Trigger update command
     */
    public function update($sql,$data=[])
    {
        $statement = $this->db->prepare($sql);
        $statement->execute($data);
        return $statement->rowCount();
    }

    /*
    *Trigger delete command
    */
    public function delete($sql,$data=[])
    {
        $statement = $this->db->prepare($sql);
        $statement->execute($data);
        return $statement->rowCount();
    }

}