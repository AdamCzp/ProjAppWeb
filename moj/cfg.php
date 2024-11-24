<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$baza = 'moja_strona';

$link = mysqli_connect($dbhost, $dbuser, $dbpass);
if (!$link) {
    die('<b>Przerwano połączenie:</b> ' . mysqli_connect_error());
}

if (!mysqli_select_db($link, $baza)) {
    die('<b>Nie wybrano bazy danych:</b> ' . mysqli_error($link));
}
?>
