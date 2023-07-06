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
    // Alors on récupère cet id
    $id=$_GET['id'];
    // Sinon, on retourne sur la page d'accueil
} else {
    redirect('index.php');
}

// Déclaration des variables
$customer=getCustomer($id);

$orders = getOrdersByCustomerNumber($id);

$orderNumber = getOrderByOrderNumber($id);

$orderDetails = getOrderDetails($id);

// Chargement du template de la page
include './templates/order.phtml';
