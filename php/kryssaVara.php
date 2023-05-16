<?php
declare (strict_types=1);

// Läs in gemensamma funktioner
require_once "funktioner.php";

// Läs och kontrollera indata
// Rätt metod
if ($_SERVER['REQUEST_METHOD']!=='POST') {
    $error=new stdClass();
    $error->meddelande=["Wrong method", "Sidan ska anropas med POST"];
    skickaJSON($error, 405);
}

// Kontrollera id
$id=filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
if(!isset($id) || !$id || $id<1) {
    $error=new stdClass();
    $error->meddelande=["Bad request", "'id' saknas eller är ogiltigt"];
    skickaJSON($error, 400);
}

// Koppla mot databasen
$db=connectDB();

// Toggla checked-värdet
$sql="UPDATE varor SET checked=NOT(checked) where id=:id";
$stmt=$db->prepare($sql);
$stmt->execute(['id'=>$id]);

if($stmt->rowCount()===0) {
    $error=new stdClass();
    $error->meddelande=["Bad request", "Kunde inte uppdatera varan"];
    skickaJSON($error, 400);
}   

// Skicka svar
skickaJSON(['meddelande'=>'OK']);