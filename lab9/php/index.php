<?php
/**
 * Projekt: Filmy Oscarowe
 * Wersja: v1.8
 * Autor: Adam Czaplicki (AC 229)
 * Grupa: 1
 * Data: [Podaj datę aktualizacji]
 */

// Konfiguracja błędów – wyłączenie notatek i ostrzeżeń
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

// Ładowanie konfiguracji i modułów
include('cfg.php'); // Plik konfiguracji
include('showpage.php'); // Obsługa strony
include('admin.php'); // Panel administracyjny
include('contact.php'); // Obsługa kontaktów

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <!-- Nagłówki i meta dane -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="content-language" content="pl"/>
    <meta name="Author" content="AC 229">
    <title>Filmy Oscarowe</title>

    <!-- Skrypty zewnętrzne -->
    <script src="../js/timedate.js" type="text/javascript"></script>
    <script src="../js/kolorujto.js" type="text/javascript"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Stylizacja -->
    <link rel="stylesheet" href="../css/css.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body onload="startClock()">
    <!-- Dynamiczny zegarek i data -->
    <div id="zegarek"></div>
    <div id="data"></div>

    <div class="conteiner">
        <!-- Nagłówek -->
        <header class="header">
            <a href="index.php?id=filmy"><h1>Filmy Oscarowe ostatnich lat</h1></a>
        </header>

        <!-- Nawigacja -->
        <nav class="nava">
            <ul class="Tytularna">
                <?php
                // Lista filmów z danymi dynamicznymi
                $movies = [
                    ['title' => 'Oppenheimer', 'image' => '../img/z1.jpg'],
                    ['title' => 'Wszystko wszędzie naraz', 'image' => '../img/z2.jpg'],
                    ['title' => 'Coda', 'image' => '../img/z3.jpg'],
                    ['title' => 'Nomadland', 'image' => '../img/z4.jpg'],
                    ['title' => 'Parasite', 'image' => '../img/z5.jpg']
                ];

                foreach ($movies as $movie) {
                    echo "<li class='tytuly'>
                            <div class='tytultekst'>{$movie['title']}</div>
                            <a href='index.php?id={$movie['title']}'>
                                <img src='{$movie['image']}' alt='{$movie['title']}'>
                            </a>
                          </li>";
                }
                ?>
            </ul>
        </nav>
    </div>

    <!-- Główna treść strony -->
    <div class="content">
        <?php
        // Bezpieczne pobieranie parametrów GET
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        // Ładowanie treści w zależności od ID
        if ($id === 'Oppenheimer') {
            include 'Oppenheime.html';
        } elseif ($id === 'Wszystko_wszędzie_naraz') {
            include 'Wszystko_wszędzie_naraz.html';
        } elseif ($id === 'Coda') {
            include 'Coda.html';
        } elseif ($id === 'Nomadland') {
            include 'Nomadland.html';
        } elseif ($id === 'Parasite') {
            include 'Parasite.html';
        } elseif ($id === 'filmy') {
            include 'filmy.html';
        } else {
            echo "<h2>Witamy na stronie Filmy Oscarowe</h2><p>Wybierz film, aby zobaczyć szczegóły.</p>";
        }
        ?>
    </div>

    <!-- Sekcja kontaktowa -->
    <div class="kontakt">
        <a href="index.php?id=Kontakt"><h3>Kontakt</h3></a>
    </div>

    <?php
    if ($id === 'Kontakt') {
        include 'Kontakt.html';
    }
    ?>

    <!-- Stopka z informacjami -->
    <?php
    $nr_indeksu = '169229';
    $nr_grupy = '1';
    echo 'Adam Czaplicki grupa ' . htmlspecialchars($nr_grupy) . '<br />';
    echo ' nr indeksu ' . htmlspecialchars($nr_indeksu) . '<br />';
    ?>
</body>
</html>
