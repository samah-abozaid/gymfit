<?php
abstract class AbstractManager
{
    protected PDO $db;

    public function __construct()
    {
        try {
            $connexion = "mysql:host=".$_ENV["DB_HOST"].";port=3306;charset=".$_ENV["DB_CHARSET"].";dbname=".$_ENV["DB_NAME"];
            $this->db = new PDO(
                $connexion,
                $_ENV["DB_USER"],
                $_ENV["DB_PASSWORD"]
            );
            // pour activer les exception en PDO
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        }catch (PDOException $e){
            // Toujours dans les logs serveur, jamais à l'écran en production
            error_log('Database connection failed: ' . $e->getMessage());

            if (($_ENV['APP_ENV'] ?? 'production') === 'development') {
                die('Connexion échouée : ' . $e->getMessage());
            }

            http_response_code(503);
            die('Service temporarily unavailable. Please try again later.');
        }

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