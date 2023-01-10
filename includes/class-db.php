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
    public function select($sql,$data=[])
    {
        $statement=$this->db->prepare($sql);
        $statement->execute($data);
        return $statement->fetch(PDO::FETCH_ASSOC);
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
    public function update()
    {

    }

    /*
    *Trigger delete command
    */
    public function delete()
    {

    }

}