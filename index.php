// FAIRE LE PHTML D'ORDER (TABLEAU)

<?php
// Cargement des librairie
require_once './lib/debug.php';
require_once './lib/format.php';


// Chargement du model
require_once './model/database.php';
require_once './model/products.php';
require_once './model/customers.php';
require_once './model/employees.php';

// Déclaration des variables
$outOfStock = getOutOfStockProducts();

$bestSellers = getBestSellersProducts() ;

$bestCustomers = getBestCustomers();

$getBestEmployees = getBestEmployees();

$productLines = getNbOfProductsByLines();

// Chargement du template de la page
include './templates/index.phtml';