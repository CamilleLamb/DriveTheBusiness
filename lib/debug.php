<?php

// Fonction debug
function debug(mixed $var, string $text = ''):void{

    // Si la variable est un tableau ou un objet, afficher en texte préformaté
    if (is_array($var) ||  is_object($var)){
        if ($text){
            echo $text . ' : ' . '<br>';
        }

        echo '<pre>';
        print_r($var);
        echo '</pre>';
    } else {

        // Sinon, vérifier s'il s'agit d'un booléen. Dans ce cas, on l'affiche en chaîne de caractère correspondant (true / false)
        if ($var === true){
            $var = ('true');
        }
        if ($var === false){
            $var = ('false');
        }

        // S'il existe un texte, l'afficher avant la variable
        if ($text){
            echo $text . ' : ' . $var. '<br>';
        // Sinon, afficher seulement la variable
        } else {
            echo $var. '<br>';
        }
    }
}

// Raccourci appelant la fonction debug
function d(mixed $var, string $text = ''):void{
    debug($var, $text);
}