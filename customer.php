<?php

// Chargement des librairies
require_once './lib/debug.php';
require_once './lib/format.php';
require_once './lib/route.php';

// Chargement du model
require_once './model/database.php';
require_once './model/customers.php';
require_once './model/orders.php';
require_once './model/products.php';

// Si un id est reçu dans l'url
if (isset($_GET['id'])) {
    // On le récupère
    $id=$_GET['id'];
    // Sinon, on retourne sur la page d'accueil
} else {
    redirect('index.php');
}
// Déclaration de la variable customer
$customer=getCustomer($id);
//d($customer);

// Déclaration de la variable orders
$orders = getOrdersByCustomerNumber($id);

// Déclaration de la variable ordersDetails
$ordersDetails = getOrderByOrderNumber($id);
//d($ordersDetails);

// Chargement du template de la page
include './templates/customer.phtml';

