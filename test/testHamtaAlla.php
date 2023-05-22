<?php
declare(strict_types=1);

try {
    // Skapa ett "handle" till cUrl för att läsa svaret från angiven sida
    $ch = curl_init('http://localhost/inkopslista/php/hamtaAlla.php');
    //return the transfer as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // Testa!
    testOK($ch);

} catch (Exception $e) {
    echo "<p class='error'>";
    echo "Något gick JÄTTEfel!<br>";
    echo $e->getMessage();
    echo "</p>";
} finally {
    // Stäng "handle" för att frigöra resurser
    curl_close($ch);
}

function testOK($curlHandle)
{
    // Anropar hämta alla och sparar svaret i en variabel
    $svarJSON = curl_exec($curlHandle);

    // GÖr om till objekt
    $svar = json_decode($svarJSON);
    if (is_array($svar)) { // Svaret är en array
        if (count($svar) > 0) { // Det finns radera
            echo "<p class='ok'>Hämta alla OK, " . count($svar) . " rader returnerades</p>";
        } else {
            echo "<p class='ok'>Hämta alla OK, inga rader fanns</p>";
        }
    } else {
        echo "<p class='error'>Hämta alla misslyckades</p>";
    }
}