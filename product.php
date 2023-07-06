<?php 
//chargement des librairies
require_once './lib/debug.php';
require_once './lib/format.php';
require_once './lib/route.php';

//chargement du model
require_once './model/database.php';
require_once './model/products.php';
require_once './model/orders.php';

// Si on un id est reçu dans l'url
if (isset($_GET['id'])) {
    // Alors on récupère cet id
    $id=$_GET['id'];
    // Sinon, on retourne sur la page d'accueil
} else {
    redirect('index.php');
}

// Récupère le détail du produit
$product = getProduct($id);

// Si le produit n'existe pas
if(empty($product)) {
    // Retour page d'accueil
    redirect('index.php');
}
// Déclaration des variables
$orders = getOrdersByProductCode($id);

$customerNumber = getOrdersByCustomerNumber($id);

// Chargement du template de la page
include './templates/product.phtml';

