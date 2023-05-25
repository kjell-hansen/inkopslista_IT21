<?php
declare(strict_types=1);

require_once "gemensammaTester.php";

?>
<!DOCTYPE html>
<html>

<head>
    <title>Testsida för inköpslistan</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="test.css">
</head>

<body>
    <h1>Testsida för API-anrop</h1>
    <h2>Hämta alla varor</h2>
    <?php require_once "testHamtaAlla.php" ?>
    <h2>Kryssa vara</h2>
    <?php require_once "testKryssaVara.php" ?>
    <h2>Radera alla varor</h2>
    <?php require_once "testRaderaAllaVaror.php" ?>
    <h2>Radera valda varor</h2>
    <?php require_once "testRaderaValda.php" ?>
    <h2>Radera enskild vara</h2>
    <?php require_once "testRaderaVara.php" ?>
    <h2>Spara vara</h2>
    <?php require_once "testSparaVara.php" ?>
    <h2>Uppdatera vara</h2>
    <?php require_once "testUppdateraVara.php" ?>
</body>

</html>