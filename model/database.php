<?php
// Connexion à la BDD avec l'invocation de pilote
function connect(){
    
$dsn = 'mysql:dbname=classicmodels;host=127.0.0.1';
$user = // Nom du user;
$password = // Mot de passe;
$database = new PDO($dsn, $user, $password);

return $database;
}
?>