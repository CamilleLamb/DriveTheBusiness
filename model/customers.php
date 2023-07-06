<?php 
require_once './model/database.php';

// Renvoie la liste des meilleurs clients
function getBestCustomers(): array {

$database = connect();
    //Code SQL à exécuter pour récupérer les données nécessaires
    $SQL = 'SELECT `customers`.`customerNumber`,`customers`.`customerName`,ROUND(SUM(`orderdetails`.`quantityOrdered`*`orderdetails`.`priceEach`), 2) AS `CA`
    FROM `customers` 
    JOIN `orders` ON `orders`.`customerNumber`=`customers`.`customerNumber`
    JOIN `orderdetails` ON `orderdetails`.`orderNumber`=`orders`.`orderNumber`
    GROUP BY `customers`.`customerNumber`
    ORDER BY `CA`DESC
    LIMIT 3;';
    // Préparation de la requête
    // /!\ NE JAMAIS OUBLIER DE PREPARER LA REQUÊTE AVANT DE L'EXECUTER (faille sécurité !)
    $query = $database->prepare($SQL);
    // Execution de la requête
    $query->execute();
    // Récupération des données de la requête
    $datas = $query->fetchAll(PDO::FETCH_ASSOC);
    // Renvoi des datas finales
    return $datas;
}
// Renvoi les données du client spécifié
function getCustomer(int $customerNumber): array {
//int car customerNumber est nombre entier dans la barre http
    $database = connect();

    $SQL = 'SELECT`customers`.`customerNumber`, 
    `customers`.`customerName`, 
    `customers`.`contactLastName`, 
    `customers`.`contactFirstName`, 
    `customers`.`phone`, 
    `customers`.`addressLine1`, 
    `customers`.`addressLine2`, 
    `customers`.`city` AS customerCity, 
    `customers`.`state`, 
    `customers`.`postalCode`, 
    `customers`.`country` AS customerCountry, 
    `customers`.`salesRepEmployeeNumber`, 
    `customers`.`creditLimit`, 
    `employees`.`lastName`, 
    `employees`.`firstName`, 
    `employees`.`email`, 
    `offices`.`city` AS officeCity, 
    `offices`.`country` AS officeCountry, 
    ROUND(SUM(`orderdetails`.`quantityOrdered`*`orderdetails`.`priceEach`), 2) AS `CA`
        FROM `customers`
        JOIN `employees` ON `employeeNumber` = `customers`.`salesRepEmployeeNumber`
        JOIN `offices` ON `offices`.`officeCode` = `employees`.`officeCode`
        JOIN `orders` ON `orders`.`customerNumber`=`customers`.`customerNumber`
        JOIN `orderdetails`ON `orderdetails`.`orderNumber`=`orders`.`orderNumber`
        WHERE `customers`.`customerNumber` = :customerNumber
        GROUP BY `customers`.`customerNumber`;';

    $query = $database->prepare($SQL);
    // Execution de la requête
    $query->execute([
        ':customerNumber'=>$customerNumber
    ]); // /!\ var remplacée token pour empêcher injections SQL
    // ON NE MET JAMAIS DE $ (variable) DANS LE SQL ! FAILLE DE SECURITE

    //Récupération des données de la requête
    $datas = $query->fetch(PDO::FETCH_ASSOC);
    // S'il n'y a pas de résultat
    if($datas ===false)
    // Renvoyer un tableau vide
    $datas = [];
    // Renvoi des datas finales
    return $datas;
}

