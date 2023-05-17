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

// Radera alla varor
$sql = "DELETE FROM varor";
$db->query($sql);

// Skicka svar
skickaJSON(["meddelande" => "OK"]);