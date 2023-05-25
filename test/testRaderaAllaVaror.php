<?php
declare(strict_types=1);
require_once "../php/funktioner.php";

try {
    // Skapa handle till cUrl för att läsa svaret
    $ch = curl_init('http://localhost/inkopslista/php/raderaAllaVaror.php');

    // Se till att vi får svaret som en sträng
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Anropen till sidan som ska testas
    // Fel metod
    echo "<p class='info'>Test fel metod</p>";
    felMetod($ch);

    // Anropa med rätt metod - Test ok
    echo "<p class='info'>Test med POST-anrop</p>";
    raderaAlla($ch);

} catch (Exception $e) {
    echo "<p class='error'>";
    echo "Något gick JÄTTEfel!<br>";
    echo $e->getMessage();
    echo "</p>";
}

function raderaAlla($curlHandle)
{
    // Koppla databas
    $db = connectDB();

    // Hämta alla varor (för att spara tillbaka sen)
    $varor = hamtaAllaVaror();

    // Anropa sidan
    curl_setopt($curlHandle, CURLOPT_POST, true);
    $jsonSvar = curl_exec($curlHandle);
    $status = curl_getinfo($curlHandle, CURLINFO_RESPONSE_CODE);

    // Kontrollera svaret
    if ($status === 200) {
        echo "<p class='ok'>Radera alla varor lyckades</p>";
    } else {
        echo "<p class='error'>Kunde inte radera alla varor, status=$status istället för 200</p>";
    }
    // Lägg tillbaka datan
    aterstallDB($varor);
}