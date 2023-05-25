<?php
declare(strict_types=1);
require_once "../php/funktioner.php";

try {
    // Skapa handle till cUrl för att läsa svaret
    $ch = curl_init('http://localhost/inkopslista/php/raderaVara.php');

    // Se till att vi får svaret som en sträng
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Anropen till sidan som ska testas
    // Fel metod
    echo "<p class='info'>Test fel metod</p>";
    felMetod($ch);

    // Anropa utan id
    echo "<p class='info'>Testa anropa utan id</p>";
    idSaknas($ch);

    // Anropa med id som inte finns
    echo "<p class='info'>Testa anropa med id som inte finns</p>";
    idFinnsInte($ch);

    // Anropa med ogiltigt id (-1)
    echo "<p class='info'>Testa anropa med ogiltigt id (-1)</p>";
    idNegativt($ch);

    // Anropa med felaktigt id (bokstav)
    echo "<p class='info'>Testa anropa med felaktigt id (bokstav)</p>";
    idBokstav($ch);

    // Anropa med id som finns - OK
    echo "<p class='info'>Testa korrekt anrop</p>";
    idOKRaderaVara($ch);

} catch (Exception $e) {
    echo "<p class='error'>";
    echo "Något gick JÄTTEfel!<br>";
    echo $e->getMessage();
    echo "</p>";
}

function idOKRaderaVara($curlHandle)
{
    // Koppla databas
    $db = connectDB();

    // Skapa vara
    $id = skapaVara('test');

    // Anropa sidan
    curl_setopt($curlHandle, CURLOPT_POST, true);
    $data = ['id' => $id];
    curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $data);
    $jsonSvar = curl_exec($curlHandle);
    $status = curl_getinfo($curlHandle, CURLINFO_RESPONSE_CODE);

    // Kontrollera svaret
    if ($status === 200) {
        echo "<p class='ok'>Radera vara fungerade som förväntat</p>";
    } else {
        echo "<p class='error'>Radera vara returnerade status=$status istället för förväntat 200</p>";
        raderaVara($id);
    }

}