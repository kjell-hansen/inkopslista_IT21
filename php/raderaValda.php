<?php
declare(strict_types=1);

// Lägg till gemensamma funktioner
require_once "funktioner.php";

// Kontrollera metod
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $error = new stdClass();
    $error->meddelande = ["Wrong method", "Sidan ska anropas med POST"];
    skickaJSON($error, 405);
}

// Koppla databas
$db = connectDB();

// Radera valda varor
$sql = "DELETE FROM varor WHERE checked=1";
$stmt = $db->query($sql);

// Skicka svar
$out = new stdClass();
$out->meddelande = "OK";
skickaJSON($out);