<?php
declare(strict_types=1);
require_once "../php/funktioner.php";

try {
    // Skapa handle till cUrl för att läsa svaret
    $ch = curl_init('http://localhost/inkopslista/php/sparaVara.php');

    // Se till att vi får svaret som en sträng
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Anropen till sidan som ska testas

    // Fel anrop (GET)
    echo "<p class='info'>Test av fel anropsmetod</p>";
    felMetod($ch);

    // Vara saknas
    echo "<p class='info'>Test vara saknas</p>";
    varaSaknas($ch);

    // Vara är >50 tecken
    echo "<p class='info'>Test vara längre än 50 tecken</p>";
    varaForLangtNamn($ch);

    // Vara är ok!
    echo "<p class='info'>Test vara ok</p>";
    varaOK($ch);

} catch (Exception $e) {
    echo "<p class='error'>";
    echo "Något gick JÄTTEfel!<br>";
    echo $e->getMessage();
    echo "</p>";
} finally {
    // Stäng handle till curl
    curl_close($ch);
}

function varaOK($curlHandle)
{
    // Koppla databas och sätt möjlighet att ångra förändringar
    $db = connectDB();

    // Sätt anrop till POST
    curl_setopt($curlHandle, CURLOPT_POST, true);

    // Lägg till vara med långt namn
    $data = ['vara' => 'Bra varunamn'];
    curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $data);

    // Gör anrop och ta hand om retursträng
    $jsonSvar = curl_exec($curlHandle);

    // Läs status för anropet
    $status = curl_getinfo($curlHandle, CURLINFO_RESPONSE_CODE);

    // Skriv ut resultatet
    if ($status === 200) {
        echo "<p class='ok'>Förväntat svar 200</p>";
        $svar = json_decode($jsonSvar);
        $id = $svar->id;
        $db->exec("DELETE FROM varor WHERE id=$id");
    } else {
        echo "<p class='error'>Fick status=$status istället för förväntat 200</p>";
    }
}
function varaForLangtNamn($curlHandle)
{
    // Sätt anrop till POST
    curl_setopt($curlHandle, CURLOPT_POST, true);

    // Lägg till vara med långt namn
    $data = ['vara' => 'Ett jättelångt namn med massor av bokstäver som gör att det här är alldeles för långt för att få plats'];
    curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $data);

    // Gör anrop och ta hand om retursträng
    $jsonSvar = curl_exec($curlHandle);

    // Läs status för anropet
    $status = curl_getinfo($curlHandle, CURLINFO_RESPONSE_CODE);

    // Skriv ut resultatet
    if ($status === 400) {
        echo "<p class='ok'>Förväntat svar 400</p>";
    } else {
        echo "<p class='error'>Fick status=$status istället för förväntat 400</p>";
    }
}
function varaSaknas($curlHandle)
{
    // Sätt anrop till POST
    curl_setopt($curlHandle, CURLOPT_POST, true);

    // Gör anrop och ta hand om retursträng
    $jsonSvar = curl_exec($curlHandle);

    // Läs status för anropet
    $status = curl_getinfo($curlHandle, CURLINFO_RESPONSE_CODE);

    // Skriv ut resultatet
    if ($status === 400) {
        echo "<p class='ok'>Förväntat svar 400</p>";
    } else {
        echo "<p class='error'>Fick status=$status istället för förväntat 400</p>";
    }
}