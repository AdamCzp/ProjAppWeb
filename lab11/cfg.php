<?php
// Dane połączenia z bazą danych
$host = 'localhost'; // Adres hosta
$user = 'root';      // Nazwa użytkownika (domyślnie: root)
$pass = '';          // Hasło użytkownika (domyślnie puste w XAMPP)
$dbname = 'moja_strona'; // Nazwa bazy danych

// Tworzenie połączenia
$db = new mysqli($host, $user, $pass, $dbname);

// Sprawdzanie poprawności połączenia
if ($db->connect_error) {
    die("Błąd połączenia z bazą danych: " . $db->connect_error);
}
?>

