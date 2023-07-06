<?php

// Change l'entier ou le float en un string représentant des euros

function euro(int|float $number): string {
    $fmt = numfmt_create( 'fr_FR', NumberFormatter::CURRENCY );
    return numfmt_format_currency($fmt, $number, "EUR");
}