<?php
/**
 * Projekt: Filmy Oscarowe
 * Wersja: v2
 * Autor: Adam Czaplicki (AC 229)
 * Grupa: 1
 */

// Konfiguracja błędów – wyłączenie notatek i ostrzeżeń w środowisku produkcyjnym
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

// Ładowanie konfiguracji i modułów
include('../cfg.php'); // Plik konfiguracji
include('../showpage.php'); // Obsługa strony

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <!-- Nagłówki i meta dane -->
    <meta charset="UTF-8">
    <meta http-equiv="content-language" content="pl"/>
    <meta name="author" content="AC 229">
    <title>Filmy Oscarowe</title>

    <!-- Skrypty zewnętrzne -->

    <script src="../js/timedate.js" type="text/javascript"></script>
    <script src="../js/kolorujto.js" type="text/javascript"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Stylizacja -->
    <link rel="stylesheet" href="../css/css1.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body onload="startClock()">
    <!-- Dynamiczny zegarek i data -->
    <div id="zegarek"></div>
    <div id="data"></div>
    <FORM METHOD="POST" NAME="background">
    <INPUT TYPE="button" VALUE="zółty" ONCLICK="changeBackground('#FFF000')">
</FORM>

    <div class="container">
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
                    ['title' => 'Wszystko_wszędzie_naraz', 'image' => '../img/z2.jpg'],
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
        include '../contact.php';
    }
    ?>

    <!-- Stopka z informacjami -->
    <?php
    $nr_indeksu = '169229';
    $nr_grupy = '1';
    echo 'Adam Czaplicki grupa ' . htmlspecialchars($nr_grupy) . '<br />';
    echo ' nr indeksu ' . htmlspecialchars($nr_indeksu) . '<br />';
    ?>
    <div id="animacjaTestowa1" class="test-block">Kliknij, a się powiększe</div>

<script>
$("#animacjaTestowa1").on("click", function(){
    $(this).animate({
        width: "500px",
        opacity: 0.4,
        fontSize: "3em",
        borderWidth: "10px"
    }, 1500);
});
</script>
<div id="animacjaTestowa2" class="test-block">
    Najedź kursorem, a się powiększe
</div>

<script>
$("#animacjaTestowa2").on({
    "mouseover" : function() {
        $(this).animate({
            width: 300
        }, 800);
    },
    "mouseout" : function() {
        $(this).animate({
            width: 200
        }, 800);
    }
});
</script>
<!-- Div, który będzie animowany przy kliknięciu -->
<div id="animacjaTestowa3" class="test-block">
    Klikaj, abym urósł
</div>

<!-- Skrypt jQuery do animacji elementu -->
<script>
$("#animacjaTestowa3").on("click", function(){
    if (!$(this).is(":animated")) {
        $(this).animate({
            width: "+=" + 50,
            height: "+=" + 10,
            opacity: "-=" + 0.1,
            duration : 3000 //Inny sposób deklaracji czasu trwania animacji
        });
    }
});
</script>

</body>
</html>

