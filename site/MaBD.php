<?php
// Classe de connexion à une base de données
// S'inspire du pattern singleton pour n'ouvrir qu'une seule connexion
// Utilisation :
//    $bd = MaBD::getInstance(); // $bd est un objet PDO
class MaBD {

   // Paramètres pour l'accès à la base
   static private $host = "gigondas";
   static private $base = "figuetm";
   static private $user = "figuetm";
   static private $password = "jasougland";
   static private $charset = "utf8";

   static private $pdo = null; // Le singleton

   // Obenir le singleton
   static function getInstance() {
      if (self::$pdo == null) {
         $dsn = sprintf("mysql:host=%s;dbname=%s;charset=%s", self::$host, self::$base, self::$charset);
         try {
            self::$pdo = new PDO($dsn, self::$user, self::$password);
         } catch (PDOException $e) {
            exit('<p class="erreur">Erreur de connexion au serveur '.self::$host.' ('.self::$user
                 .")<br/>".$e->getMessage()."</p>");
         }
      }
      return self::$pdo;
   }
}
  
?>

