<?php
declare(strict_types=1);
require_once "../php/funktioner.php";

try {
    // Skapa handle till cUrl för att läsa svaret
    $ch = curl_init('http://localhost/inkopslista/php/kryssaVara.php');

    // Se till att vi får svaret som en sträng
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Anropen till sidan som ska testas
    // Fel metod
    echo "<p class='info'>Test fel metod</p>";
    felMetod($ch);

    // id saknas i anropet
    echo "<p class='info'>ID saknas i anropet</p>";
    idSaknas($ch);

    // id felaktigt (inte en siffra)
    echo "<p class='info'>Felaktigt id (inte en siffra)</p>";
    idBokstav($ch);

    // id felaktigt (-1)
    echo "<p class='info'>Felaktigt id (-1)</p>";
    idNegativt($ch);

    // angivet id saknas
    echo "<p class='info'>Angivet id saknas</p>";
    idFinnsInte($ch);

    // OK - sätt kryss
    echo "<p class='info'>Test OK - sätt kryss</p>";
    sattKryss($ch);

    // OK - ta bort kryss
    echo "<p class='info'>Test OK - ta bort kryss</p>";
    tabortKryss($ch);

} catch (Exception $e) {
    echo "<p class='error'>";
    echo "Något gick JÄTTEfel!<br>";
    echo $e->getMessage();
    echo "</p>";
} finally {
    curl_close($ch);
}

function tabortKryss($curlHandle)
{
    // Koppla mot databas
    $db = connectDB();

    // Lägg till vara
    $id = skapaVara('test');

    // Sätt kryss och kontrollera att det är satt
    kryssaVara($id);
    $vara = lasVara($id);
    if (!$vara->checked) {
        echo "<p class='error'>Kunde inte sätta kryss, avbryter testet</p>";
        raderaVara($id);
    }

    // Anropa sidan 
    curl_setopt($curlHandle, CURLOPT_POST, true);
    $data = ['id' => $id];
    curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $data);
    $jsonSvar = curl_exec($curlHandle);
    $status = curl_getinfo($curlHandle, CURLINFO_RESPONSE_CODE);

    // Skriv ut svaret
    if ($status === 200) {
        echo "<p class='ok'>Kryssa av vara fungerade</p>";
    } else {
        echo "<p class='error'>Kryssa av vara fungerade inte, status=$status istället för 200</p>";
    }

    // Radera vara
    raderaVara($id);
}

function sattKryss($curlHandle)
{
    // Koppla mot databas
    $db = connectDB();

    // Skapa post
    $id = skapaVara('test');

    // Kontrollera att kryss är tomt - läs vara och kontrollera svaret
    $vara = lasVara($id);
    if ($vara->checked) {
        echo "<p class='error'>Kunde inte skapa vara utan kryss</p>";
        raderaVara($id);
        return;
    }

    // Sätt kryss - anropa sidan med POST och rätt data
    curl_setopt($curlHandle, CURLOPT_POST, true);
    $data = ['id' => $id];
    curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $data);

    $jsonSvar = curl_exec($curlHandle);
    $status = curl_getinfo($curlHandle, CURLINFO_RESPONSE_CODE);

    // Skriv ut resultat
    if ($status === 200) {
        echo "<p class='ok'>Kryssa vara fungerade</p>";
    } else {
        echo "<p class='error'>Kryssa vara fungerade inte, status=$status istället för 200</p>";
    }

    // Radera post
    raderaVara($id);
}