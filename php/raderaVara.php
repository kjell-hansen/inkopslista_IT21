<?php
declare(strict_types=1);

// Koppla gemensamma funktioner
require_once "funktioner.php";

// Läs och kontrollera indata
// Rätt metod
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $error = new stdClass();
    $error->meddelande = ["Wrong method", "Sidan ska anropas med POST"];
    skickaJSON($error, 405);
}

// Kontrollera id
$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
if (!isset($id) || !$id || $id < 1) {
    $error = new stdClass();
    $error->meddelande = ["Bad request", "'id' saknas eller är ogiltigt"];
    skickaJSON($error, 400);
}

// Koppla databas
$db = connectDB();

// Skapa sql och exekvera den
$sql = "DELETE FROM varor WHERE id=:id";
$stmt = $db->prepare($sql);
$stmt->execute(['id' => $id]);

// Skicka svar
$out = new stdClass();
if ($stmt->rowCount() === 0) {
    $out->meddelande = ["Posten kunde inte raderas"];
    skickaJSON($out, 400);
} else {
    $out->meddelande = ["OK"];
    skickaJSON($out);
}