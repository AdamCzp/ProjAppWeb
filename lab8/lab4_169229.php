<?php
$nr_indeksu = '169229'; 
$nr_grupy = '1';        

echo 'Adam Czaplicki grupa ' ,$nr_grupy, '<br />';
echo ' nr indeksu ' ,$nr_indeksu, '<br />'; 


// a) Metoda include() i require_once()
// include('config.php');  
// require_once('functions.php'); 

// b) Warunki if, else, elseif, switch
$wiek = 18;

// Przykład if, else, elseif
if ($wiek < 18) {
    echo "Niepełnoletni";
} elseif ($wiek == 18) {
    echo "Właśnie osiągnąłeś pełnoletność";
} else {
    echo "Pełnoletni";
}
echo '<br />';

// Przykład switch
$dzien = "Poniedziałek";
switch ($dzien) {
    case "Poniedziałek":
        echo "Początek tygodnia ";
        break;
    case "Piątek":
        echo "Koniec tygodnia ";
        break;
    default:
        echo "Środek tygodnia ";
}

// c) Pętla while() i for()
// Przykład while
$i = 0;
while ($i < 5) {
    echo $i, '  ';
    $i++;
}

// Przykład for
for ($j = 0; $j < 5; $j++) {
    echo $j, '  ';
}

// d) Typy zmiennych $_GET, $_POST, $_SESSION
// Przykład $_GET
echo $_GET['imie']; 

// Przykład $_POST
echo $_POST['haslo']; 

// Przykład $_SESSION
session_start(); 
$_SESSION['uzytkownik'] = "Jan"; 
echo $_SESSION['uzytkownik']; 

?>