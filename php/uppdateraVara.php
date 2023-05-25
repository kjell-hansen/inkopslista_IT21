<?php
declare(strict_types=1);

// Inkludera gemensamma funktioner
require_once "funktioner.php";

// Kontrollera metod
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $error = new stdClass();
    $error->meddelande = ["Wrong method", "Sidan ska anropas med POST"];
    skickaJSON($error, 405);
}

// Kontrollera indata
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$vara = filter_input(INPUT_POST, 'vara', FILTER_SANITIZE_SPECIAL_CHARS);

// Skapa ett objekt för felmeddelanden
$error = new stdClass();
$error->meddelande = [];
if (!isset($id) || $id === false || $id < 1) {
    // Felaktigt id, lägg till meddelande till felobjektet
    $error->meddelande[] = "'id' saknas eller är felaktigt";
}

if (!isset($vara) || mb_strlen($vara) > 50) {
    // Felaktig vara, lägg till meddelande till felobjektet
    $error->meddelande[] = "'vara' saknas eller är för långt";
}

// Har felobjektet några meddelanden 
if (count($error->meddelande) > 0) {
    // Lägg till ett generellt meddelande först i arrayen
    array_unshift($error->meddelande, "Bad request");
    skickaJSON($error, 400);
}

// Koppla databas
$db = connectDB();

// Uppdatera tabellen
$sql = "UPDATE varor SET namn=:vara WHERE id=:id";
$stmt = $db->prepare($sql);

$stmt->execute(['id' => $id, 'vara' => $vara]);

// Returnera svar
if ($stmt->rowCount() > 0) {
    $out = new stdClass();
    $out->meddelande = "OK";
    skickaJSON($out);
} else {
    $error = new stdClass();
    $error->meddelande = ["Okänt fel", "Kunde inte uppdatera vara"];
    skickaJSON($error, 400);
}