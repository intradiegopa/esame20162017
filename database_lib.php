<?php
class DB {

        private $pdo;

        public function __construct($host, $dbname, $username, $password) {
                 try{
                    $pdo = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8', $username, $password);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $this->pdo = $pdo;
                }
                catch(PDOException $e){
                    //$error = $e->getMessage();
                }
        }
        public function elenca_partenze() {
                $query = "SELECT viaggio.partenza
FROM viaggio 
GROUP BY viaggio.partenza
ORDER BY viaggio.partenza";
                $statement = $this->pdo->prepare($query);
                $statement->execute();
                $data = $statement->fetchAll();
                return $data;
        }
     public function elenca_destinazioni() {
                $query = "SELECT viaggio.destinazione
FROM viaggio 
GROUP BY viaggio.destinazione
ORDER BY viaggio.destinazione";
                $statement = $this->pdo->prepare($query);
                $statement->execute();
                $data = $statement->fetchAll();
                return $data;
        }
        
    
    public function query($query) {
                $statement = $this->pdo->prepare($query);
                $statement->execute();
                $data = $statement->fetchAll();
                return $data;
        }
    
    public function query_una_riga($query) {
                $statement = $this->pdo->prepare($query);
                $statement->execute();
                $data = $statement->fetch();
                return $data;
        }
    
}
?>