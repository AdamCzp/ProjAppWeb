<?php
$host = 'localhost';
$user = 'root';
$pass = '123';
$dbname = 'moja_strona';

try {
    $db = new mysqli($host, $user, $pass, $dbname);
    echo "Połączenie z bazą danych działa!";
} catch (mysqli_sql_exception $e) {
    echo "Błąd połączenia z bazą danych: " . $e->getMessage();
}
?>

