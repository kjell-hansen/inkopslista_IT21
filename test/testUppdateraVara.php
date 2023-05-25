<?php
declare(strict_types=1);
require_once "../php/funktioner.php";

try {
    // Skapa handle till cUrl för att läsa svaret
    $ch = curl_init('http://localhost/inkopslista/php/uppdateraVara.php');

    // Se till att vi får svaret som en sträng
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Anropen till sidan som ska testas
    // Fel metod
    echo "<p class='info'>Test fel metod</p>";
    felMetod($ch);

    // Anropa utan id
    echo "<p class='info'>Testa anrop utan id</p>";
    idSaknas($ch, "Nyttnamn");

    // Anropa med ogiltigt id (-1)
    echo "<p class='info'>Testa anropa med ogiltigt id (-1)</p>";
    idNegativt($ch, "Nyttnamn");

    // Anropa med ogiltigt id (bokstav)
    echo "<p class='info'>Testa anropa med bokstav i id</p>";
    idBokstav($ch, "Nyttnamn");

    // Anropa med id som inte finns
    echo "<p class='info'>Testa anropa med id som inte finns</p>";
    idFinnsInte($ch, "Nyttnamn");

    // Anropa utan vara
    echo "<p class='info'>Testa anropa uppdatering utan vara</p>";
    uppdateraVaraSaknas($ch);

    // Uppdatera med för lång vara (>50 tecken)
    echo "<p class='info'>Testa uppdatera med vara med för långt namn</p>";
    uppdateraForLangtNamn($ch);

    // Uppdatera ok!
    echo "<p class='info'>Uppdatera ok!</p>";
    uppdateraOK($ch);

} catch (Exception $e) {
    echo "<p class='error'>";
    echo "Något gick JÄTTEfel!<br>";
    echo $e->getMessage();
    echo "</p>";
}

function uppdateraOK($curlHandle)
{
    // Koppla databas
    $db = connectDB();

    // Skapa post
    $id = skapaVara('test');

    // Sätt data och skicka anrop
    $data = ['id' => $id, 'vara' => 'Kort'];
    curl_setopt($curlHandle, CURLOPT_POST, true);
    curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $data);

    $jsonSvar = curl_exec($curlHandle);
    $status = curl_getinfo($curlHandle, CURLINFO_RESPONSE_CODE);

    // Kontrollera svar
    if ($status === 200) {
        echo "<p class='ok'>Uppdatera returnerade 200 som förväntat</p>";
    } else {
        echo "<p class='error'>Uppdatera fungerade inte, status=$status istället för förväntat 200</p>";
    }

    // Radera posten
    raderaVara($id);
}
function uppdateraForLangtNamn($curlHandle)
{
    // Koppla databas
    $db = connectDB();

    // Skapa post
    $id = skapaVara('test');

    // Sätt data och skicka anrop
    $data = ['id' => $id, 'vara' => 'För lång text för att få plats i databasen, 50 tecken är allt som får plats'];
    curl_setopt($curlHandle, CURLOPT_POST, true);
    curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $data);

    $jsonSvar = curl_exec($curlHandle);
    $status = curl_getinfo($curlHandle, CURLINFO_RESPONSE_CODE);

    // Kontrollera svar
    if ($status === 400) {
        echo "<p class='ok'>Uppdatera med för långt namn returnerade 400 som förväntat</p>";
    } else {
        echo "<p class='error'>Uppdatera med för långt namn fungerade inte, status=$status istället för förväntat 400</p>";
    }

    // Radera posten
    raderaVara($id);
}

function uppdateraVaraSaknas($curlHandle)
{
    // Koppla databas
    $db = connectDB();

    // Skapa ny post
    $id = skapaVara('Test');

    // Sätt data och anropa sidan
    curl_setopt($curlHandle, CURLOPT_POST, true);
    $data = ['id' => $id];
    curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $data);

    $jsonSvar = curl_exec($curlHandle);
    $status = curl_getinfo($curlHandle, CURLINFO_RESPONSE_CODE);

    // Kontrollera svar
    if ($status === 400) {
        echo "<p class='ok'>Uppdatera vara utan vara returnerade 400 som förväntat</p>";
    } else {
        echo "<p class='error'>Uppdatera vara returnerade status=$status istället för 400</p>";
    }

    // Radera post
    raderaVara($id);
}