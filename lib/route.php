<?php

// Redirige l'utilisateur vers une certaine url

function redirect(string $url): void {
    header('location:' . $url);
    die();
}