<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <script src="../js/timedate.js" type="text/javascript"></script>
    <script src="../js/kolorujto.js" type="text/javascript"></script>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="content-language" content="pl"/>
    <meta name="Author" content="AC 229">
    <link rel="stylesheet" href="../css/css.css">
    <title>
        Filmy oscarowe
        </title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body onload="startClock()">
    <div id="zegarek"></div>
    <div id="data"></div>

<div class="conteiner">
    <header class="header">
        <a href="index.php?id=filmy"><h1>Filmy Oscarowe ostatnich lat</h1></a>

    </header>
    <nav class="nava">
        <ul class="Tytularna">
            <li class="tytuly">
                <div class="tytultekst">Oppenheimer</div>
                <a href="index.php?id=Oppenheimer">
                    <img src="../img/z1.jpg" alt="Obraz 1">
                </a>
            </li>
            <li class="tytuly">
                <div class="tytultekst">Wszystko wszędzie naraz</div>
                <a href="index.php?id=Wszystko_wszędzie_naraz">
                    <img src="../img/z2.jpg" alt="Obraz 2">
                </a>
            </li>
            <li class="tytuly">
                <div class="tytultekst">Coda</div>
                <a href="index.php?id=Coda">
                    <img src="../img/z3.jpg" alt="Obraz 3">
                </a>
            </li>
            <li class="tytuly">
                <div class="tytultekst">Nomadland</div>
                <a href="index.php?id=Nomadland">
                    <img src="../img/z4.jpg" alt="Obraz 4">
                </a>
            </li>
            <li class="tytuly">
                <div class="tytultekst">Parasite</div>
                <a href="index.php?id=Parasite">
                    <img src="../img/z5.jpg" alt="Obraz 5">
                </a>
            </li>
        </ul>
    </nav>
</div>

    <div class="content">
        <?php
        if ($_GET['id'] == 'Oppenheimer') {
            include 'Oppenheime.html';
        } elseif ($_GET['id'] == 'Wszystko_wszędzie_naraz') {
            include 'Wszystko_wszędzie_naraz.html';
        } elseif ($_GET['id'] == 'Coda') {
            include 'Coda.html';
        } elseif ($_GET['id'] == 'Nomadland') {
            include 'Nomadland.html';
        } elseif ($_GET['id'] == 'Parasite') {
            include 'Parasite.html';
        }elseif ($_GET['id'] == 'filmy') {
                 include 'filmy.html';
        } else {
            echo "<h2>Witamy na stronie Filmy Oscarowe</h2><p>Wybierz film, aby zobaczyć szczegóły.</p>";
        }
        ?>
    </div>

    <div class="kontakt">
        <a href="index.php?id=Kontakt"><h3>Kontakt</h3></a>
    </div>

    <?php
    if ($_GET['id'] == 'Kontakt') {
        include 'Kontakt.html';
    }
    ?>

    <?php
    $nr_indeksu = '169229';
    $nr_grupy = '1';
    echo 'Adam Czaplicki grupa ' . $nr_grupy . '<br />';
    echo ' nr indeksu ' . $nr_indeksu . '<br />';
    ?>
</body>
</html>

