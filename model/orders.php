<?php
require_once './model/database.php';

// Renvoie toutes les commandes contenant produit spécifié
function getOrdersByProductCode (string $productCode):array{
    // connexion à la BDD
    $database = connect();
    //code SQL à exécuter 
    $SQL = 'SELECT `orders`.`orderNumber`, `orders`.`orderDate`,`orders`.`customerNumber`,`customers`.`customerName`, `orderdetails`.`quantityOrdered`,`orderdetails`.`priceEach`, 
    ROUND (`orderdetails`.`quantityOrdered`*`orderdetails`.`priceEach`, 2) AS "total" 
    FROM `orders` 
    JOIN `orderdetails` ON `orderdetails`.`orderNumber` = `orders`.`orderNumber` 
    JOIN `customers` ON `customers`.`customerNumber`= `orders`.`customerNumber` 
    WHERE `orderdetails`.`productCode`= :productCode
    ORDER BY `orders`.`orderDate`DESC;';
    // préparation de la requête
    // /!\ NE JAMAIS OUBLIER DE PREPARER LA REQUÊTE AVANT DE L'EXECUTER (faille sécurité !)
    $query = $database->prepare($SQL);
    // Execution de la requête
    $query->execute([
        ':productCode'=> $productCode
    ]);// /!\ var remplacée par token pour empêcher injections SQL
    // Récupération des données sous forme de tableau associatif
    $datas = $query->fetchAll(PDO::FETCH_ASSOC);
    // Renvoi des datas finales
    return $datas;
}
// Renvoie toutes les commandes d'un client
function getOrdersByCustomerNumber (string $customerNumber):array{
    // Connexion à la BDD
    $database = connect();
    // Code SQL à exécuter 
    $SQL = 'SELECT `orders`.`orderNumber`,
    `orders`.`orderDate`,
    `orders`.`requiredDate`,
    `orders`.`shippedDate`,
    `orders`.`status`,
    SUM(`orderdetails`.`quantityOrdered`) AS `quantity`,
    ROUND(SUM(`orderdetails`.`quantityOrdered`*`orderdetails`.`priceEach`), 2) AS `total`
    FROM `orders`
    JOIN `orderdetails` ON `orderdetails`.`orderNumber` = `orders`.`orderNumber`
    WHERE `orders`.`customerNumber` = :customerNumber
    GROUP BY `orders`.`orderNumber`
    ORDER BY `orders`.`orderDate` DESC;';
    // Préparation de la requête
    // /!\ NE JAMAIS OUBLIER DE PREPARER LA REQUÊTE AVANT DE L'EXECUTER (faille sécurité !)
    $query = $database->prepare($SQL);
    // Execution de la requête
    $query->execute([
     ':customerNumber'=> $customerNumber
    ]);// /!\ var remplacée par token pour empêcher injections SQL
    // Récupération des données sous forme de tableau associatif
    $datas = $query->fetchAll(PDO::FETCH_ASSOC);
    // Renvoi des datas 
    return $datas;
}
// Renvoi de la commande à partir du numéro de commande
function getOrderByOrderNumber(int $orderNumber):array{
    // Connexion à la BDD
    $database = connect();
    // Code SQL à exécuter
    $SQL = 'SELECT `orders`.`orderNumber`, 
    `orders`.`orderDate`, 
    `orders`.`status`, 
    `orders`.`comments`,
    `customers`.`customerNumber`, 
    `customers`.`customerName`, 
    `customers`.`contactLastName`, 
    `customers`.`contactFirstName`, 
    `customers`.`phone`, `customers`.`addressLine1`, 
    `customers`.`addressLine2`, 
    `customers`.`postalCode`, 
    `customers`.`city`, 
    `customers`.`country`, 
    `customers`.`state`,
    `orderdetails`.`quantityOrdered`,
    `orderdetails`.`priceEach`,
    `orderdetails`.`orderdetails`,
    FROM `orders`
    JOIN `customers` ON `customers`.`customerNumber`=`orders`.`customerNumber`
    JOIN `orderdetails` ON `orderdetails`.`orderNumber`=`orders`.`orderNumber`
    WHERE `orders`.`orderNumber` = :orderNumber';
    // Préparation de la requête
    $query = $database->prepare($SQL);
    // Execution de la requête
    $query->execute([
    ':orderNumber'=> $orderNumber
    ]);// /!\ var remplacée par token pour empêcher injections SQL
    // Récupération des données sous forme de tableau associatif
    $datas = $query->fetch(PDO::FETCH_ASSOC);
    // Renvoi des données
    return $datas;
}
// Renvoi les détails d'une commande
function getOrderDetails (int $orderNumber):array{
    // Connexion à la BDD
    $database = connect();
    // Code SQL à exécuter 
    $SQL = 'SELECT orderdetails.productCode, 
    orderdetails.priceEach, 
    orderdetails.quantityOrdered, 
    products.productName, 
    products.productLine, 
    products.productScale, 
    ROUND(SUM(orderdetails.priceEach * orderdetails.quantityOrdered), 2) as total 
    FROM orderdetails 
    JOIN products ON products.productCode = orderdetails.productCode 
    WHERE orderdetails.orderNumber= :orderNumber 
    GROUP BY products.productCode;';
    // Préparation de la requête
    // /!\ NE JAMAIS OUBLIER DE PREPARER LA REQUÊTE AVANT DE L'EXECUTER (faille sécurité !)
    $query = $database->prepare($SQL);
    // Execution de la requête
    $query->execute([
     ':orderNumber'=> $orderNumber
    ]);// /!\ var remplacée par token pour empêcher injections SQL
    //récupération des données de la requête
    $datas = $query->fetchAll(PDO::FETCH_ASSOC);
    //renvoi des données
    return $datas;
}
// Renvoi les commandes par numéro employé
function getOrdersByEmployeeNumber (string $employeeNumber):array{
    // Connexion à la BDD
    $database = connect();
    // Code SQL à exécuter 
    $SQL = 'SELECT `orders`.`orderNumber`, 
    `orders`.`orderDate`, `orders`.`requiredDate`, 
    `orders`.`shippedDate`, 
    `orders`.`status`, 
    `customers`.`customerNumber`, 
    `customers`.`customerName`, 
    SUM(`orderdetails`.`quantityOrdered`) AS `quantity`,
    ROUND(SUM(`orderdetails`.`quantityOrdered`*`orderdetails`.`priceEach`), 2) AS `total`
    FROM `orders`
    JOIN `customers` ON `customers`.`customerNumber`=`orders`.`customerNumber`
    JOIN `employees` ON `employees`.`employeeNumber`=`customers`.`salesRepEmployeeNumber`
    JOIN `orderdetails`ON `orderdetails`.`orderNumber`=`orders`.`orderNumber`
    WHERE `employees`.`employeeNumber`= :employeeNumber
    GROUP BY `orders`.`orderNumber`
    ORDER BY `orders`.`orderDate` DESC;';
    // Préparation de la requête
    // /!\ NE JAMAIS OUBLIER DE PREPARER LA REQUÊTE AVANT DE L'EXECUTER (faille sécurité !)
    $query = $database->prepare($SQL);
    // Execution de la requête
    $query->execute([
     ':employeeNumber'=> $employeeNumber
    ]);// /!\ var remplacée par token pour empêcher injections SQL
    // Récupération des données de la requête
    $datas = $query->fetchAll(PDO::FETCH_ASSOC);
    // Renvoi des datas finales
    return $datas;
}