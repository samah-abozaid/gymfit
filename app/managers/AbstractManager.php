<?php
abstract class AbstractManager
{
    protected PDO $db;

    public function __construct()
    {
        $connexion = "mysql:host=".$_ENV["DB_HOST"].";port=3306;charset=".$_ENV["DB_CHARSET"].";dbname=".$_ENV["DB_NAME"];
        $this->db = new PDO(
            $connexion,
            $_ENV["DB_USER"],
            $_ENV["DB_PASSWORD"]
        );
    }
    
    public function countAll(): int
{
    $query = $this->db->prepare(
        "SELECT COUNT(*) FROM {$this->table}"
    );
    $query->execute();
    return (int) $query->fetchColumn();  //donne la valeur sans table 
}
}