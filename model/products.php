<?php
require_once './model/database.php';

// Renvoie la liste des produits presque épuisés
function getOutOfStockProducts ():array{
    // Connexion à la BDD
    $database = connect();
    // Code SQL à exécuter 
    $SQL = 'SELECT `productCode`,`productLine`,`productName`,`productScale`,`quantityInStock`
            FROM `products`
            WHERE `quantityInStock`<200';
    // Préparation de la requête
    // /!\ NE JAMAIS OUBLIER DE PREPARER LA REQUÊTE AVANT DE L'EXECUTER (faille sécurité !)
    $query = $database->prepare($SQL);
    // Execution de la requête
    $query->execute();
    // Récupération des données de la requête
    $datas = $query->fetchAll(PDO::FETCH_ASSOC);
    // Renvoi des données
    return $datas;
}

// Renvoie la liste des meilleures ventes
function getBestSellersProducts ():array{
        // Connexion à la BDD
        $database = connect();
        // Code SQL à exécuter 
        $SQL = 'SELECT `products`.`productName`, `products`.`productCode`,SUM(`orderdetails`.`quantityOrdered`) AS `quantity`
                FROM `products`
                JOIN `orderdetails`ON `orderdetails`.`productCode`=`products`.`productCode`
                GROUP BY `products`.`productCode`
                ORDER BY `quantity` DESC
                LIMIT 20';
        // Préparation de la requête
        $query = $database->prepare($SQL);
        // Execution de la requête
        $query->execute();
        // Récupération des données de la requête
        $datas = $query->fetchAll(PDO::FETCH_ASSOC);
        // Renvoi des données
        return $datas;
    }

    // Renvoie le nombre de produits dans chaque catégorie
function getNbOfProductsByLines(): array {
    // Connexion à la BDD
    $database = connect();
    // Code SQL à exécuter 
    $SQL = 'SELECT `productLine`, COUNT(*) AS `quantity` 
    FROM `products` 
    GROUP BY `productline`;';
    // Préparation de la requête
    // /!\ NE JAMAIS OUBLIER DE PREPARER LA REQUÊTE AVANT DE L'EXECUTER (faille sécurité !)
    $query = $database->prepare($SQL);
    // Execution de la requête
    $query->execute();
    // Récupération des données de la requête
    $datas = $query->fetchAll(PDO::FETCH_ASSOC);
    // Renvoi des données
    return $datas;
}

// Renvoie les informations du produit spécifié
function getProduct(string $productCode):array {
// Connexion à la BDD
$database = connect();
    //code SQL à exécuter 
    $SQL = 'SELECT *
    FROM `products` 
    WHERE `productCode` = :productCode';
    // Préparation de la requête
    $query = $database->prepare($SQL);
    // Execution de la requête
    $query->execute([
        ':productCode'=>$productCode
    ]); // /!\ var remplacée par token pour empêcher injections SQL
    // Récupération des données de la requête
    $datas = $query->fetch(PDO::FETCH_ASSOC);
    // S'il n'y a pas de résultat
    if($datas ===false)
    // Renvoyer un tableau vide
    $datas = [];
    //Renvoi des données
    return $datas;
}