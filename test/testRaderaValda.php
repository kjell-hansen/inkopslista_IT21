<?php
declare(strict_types=1);
require_once "../php/funktioner.php";

try {
    // Skapa handle till cUrl för att läsa svaret
    $ch = curl_init('http://localhost/inkopslista/php/raderaValda.php');

    // Se till att vi får svaret som en sträng
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Anropen till sidan som ska testas
    // Fel metod
    echo "<p class='info'>Test fel metod</p>";
    felMetod($ch);

    // Test ok
    echo "<p class='info'>Test radera valda OK</p>";
    raderaValda($ch);

} catch (Exception $e) {
    echo "<p class='error'>";
    echo "Något gick JÄTTEfel!<br>";
    echo $e->getMessage();
    echo "</p>";
}

function raderaValda($curlHandle)
{
    // Koppla databas
    $db = connectDB();

    // Läs in alla varor (för att återställa senare)
    $varor = hamtaAllaVaror();

    // Kryssa alla varor
    foreach ($varor as $value) {
        kryssaVara($value['id']);
    }

    // Anropa sidan
    curl_setopt($curlHandle, CURLOPT_POST, true);
    $jsonSvar = curl_exec($curlHandle);
    $status = curl_getinfo($curlHandle, CURLINFO_RESPONSE_CODE);

    // Kontrollera svaret
    if ($status === 200) {
        echo "<p class='ok'>Radera valda varor fungerade</p>";
    } else {
        echo "<p class='error'>Radera valda varor fungerade inte, fick status=$status istället för 200</p>";
    }
    // Återställ alla varor
    aterstallDB($varor);
}