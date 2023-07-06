// A FINIR

<?php 
require_once './model/database.php';

// Renvoie la liste des meilleurs employés
function getBestEmployees(): array {
    
    $database = connect();
    // Code SQL à exécuter 
    $SQL = 'SELECT `employees`.`employeeNumber`, `employees`.`firstName`,`employees`.`lastName`,`offices`.`city`,ROUND(SUM(`orderdetails`.`quantityOrdered`*`orderdetails`.`priceEach`), 2) AS `CA`
    FROM `employees` 
    JOIN `offices` ON `offices`.`officeCode`=`employees`.`officeCode`
    JOIN `customers` ON `customers`.`salesRepEmployeeNumber`=`employees`.`employeeNumber`
    JOIN `orders` ON `orders`.`customerNumber`=`customers`.`customerNumber`
    JOIN `orderdetails`ON `orderdetails`.`orderNumber`
    = `orders`.`orderNumber`
    GROUP BY `employees`.`employeeNumber`
    ORDER BY `CA` DESC
    LIMIT 5;';
    // Préparation de la requête
    // /!\ NE JAMAIS OUBLIER DE PREPARER LA REQUÊTE AVANT DE L'EXECUTER (faille sécurité !)
    $query = $database->prepare($SQL);
    // Execution de la requête
    $query->execute();
    // Récupération des données sous forme de tableau associatif
    $datas = $query->fetchAll(PDO::FETCH_ASSOC);
    // Renvoi des datas finales
    return $datas;
}

// Renvoi les données de l'employé spécifié
function getEmployee(int $employeeNumber):array{
//int car employeeNumber est nombre entier dans la barre http
    $database = connect();

    $SQL = 'SELECT `employees`.`lastName`, 
                    `employees`.`firstName`, 
                    `employees`.`jobTitle`, 
                    `employees`.`email`, 
                    `employees`.`employeeNumber`, 
                    `employees`.`officeCode`,
                    `employees`.`reportsTo`, 
                    `offices`.`city`, 
                    `offices`.`country`, 
                    `offices`.`phone`, 
                    `offices`.`addressLine1`, 
                    `offices`.`addressLine2`, 
                    `offices`.`postalCode`, 
                    `offices`.`city`, 
                    `offices`.`state`, 
                    `offices`.`country`,
                    `offices`.`territory`,
                    ROUND(SUM(
                FROM `employees`
                JOIN `offices` ON `offices`.`officeCode` = `employees`.`officeCode`
                JOIN `customers`ON `customers`.`salesRep = `employees`.`employeeNumber`
                JOIN `orders` ON `orders`.`customerNumber`=`customers`.`customerNumber`
                JOIN `orderdetails` ON `orderdetails`.`orderNumber`=`orders`.`orderNumber`
                WHERE `employee`.`employeeNumber` = :employeeNumber;';
// Préparation de la requête
$query = $database->prepare($SQL);
// Execution de la requête
$query->execute([
 ':employeeNumber'=> $employeeNumber
]);// /!\ var remplacée par token pour empêcher injections SQL
// Récupération des données sous forme de tableau associatif
$datas = $query->fetch(PDO::FETCH_ASSOC);
// Renvoi des données
return $datas;

}