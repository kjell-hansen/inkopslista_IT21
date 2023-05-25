<?php
declare(strict_types=1);
require_once "../php/funktioner.php";
function felMetod($curlHandle)
{
    // Gör anrop och ta hand om retursträngen
    $jsonSvar = curl_exec($curlHandle);
    // Hämta status för anropet
    $status = curl_getinfo($curlHandle, CURLINFO_RESPONSE_CODE);

    if ($status === 405) {
        echo "<p class='ok'>Svar 405 stämmer med förväntat svar</p>";
    } else {
        echo "<p class='error'>Fick status=$status istället för förväntat 405</p>";
    }
}

function skapaVara(string $vara): int
{
    $db = connectDB();
    if ($db->exec("INSERT INTO varor (namn) VALUES ('$vara')")) {
        return (int) $db->lastInsertId();
    }

    return 0;
}

function raderaVara(int $id): void
{
    $db = connectDB();
    $db->exec("DELETE FROM varor WHERE id=$id");
}

function lasVara(int $id): stdClass
{
    $db = connectDB();
    $row = $db->query("SELECT * FROM varor WHERE id=$id");
    return $row->fetchObject();
}
function kryssaVara(int $id): void
{
    $db = connectDB();
    $db->query("UPDATE varor SET checked=1 WHERE id=$id");
}
function hamtaAllaVaror(): array
{
    $db = connectDB();
    $stmt = $db->query("SELECT * FROM varor");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function aterstallDB($varor): void
{
    $db = connectDB();
    $sql = "INSERT INTO varor (id, namn, checked) VALUES (:id, :namn, :checked)";
    $stmt = $db->prepare($sql);

    foreach ($varor as $value) {
        $stmt->execute($value);
    }
}