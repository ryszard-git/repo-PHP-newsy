<?php
if (!isset($_SESSION))
{
	session_start();
}

if ($_SESSION['zalogowany']!="tak") {
	echo "<script>window.location.href = 'zaloguj.php';</script>";
	exit;
}

require_once("classes/Strona.php");
$stronka=new Strona();

if (!isset($wstaw_naglowek))
{
	$stronka->tytul="Przeglądanie wiadomości";
	$stronka->WyswietlNaglowek();
	$stronka->OtworzBlok("menugorne");
	$stronka->WyswietlMenuGlowne();
	$stronka->DomknijBlok(); //menugorne
	$stronka->WyswietlPasekLogin();
	$stronka->OtworzBlok("tresc");
	$stronka->OtworzBlok("tematy");
	echo '<p style="text-align:center;">Przeglądanie wiadomości</p><br /><br />';
}



if (isset($_GET['strona']))
{
	$strona_biezaca=$_GET['strona'];
}
else
{
	$strona_biezaca=1;
}
require "config/config.php";

$na_stronie=$ilosc_wierszy_na_stronie; // ilość elementów na stronie. zmienna $ilosc_wierszy_na_stronie odczytana z config.php
if (isset($_SESSION['tablica']))
{
	$tablica=$_SESSION['tablica'];
	$ilosc_wierszy=count($tablica); //całkowita ilość wierszy tablicy do wyświetlenia
}

$ilosc_podstron=ceil($ilosc_wierszy/$na_stronie); // liczba podstron wyliczona z zaokrągleniem w górę

$start_poprzedniej=$strona_biezaca-1;
$start_nastepnej=$strona_biezaca+1;

$i_min=$strona_biezaca*$na_stronie-$na_stronie; // indeks minimalny elementu tablicy wyświetlanej na stronie
$i_max=$strona_biezaca*$na_stronie-1; // indeks maksymalny elementu tablicy wyświetlanej na stronie

$ta_strona=ceil($i_max/$na_stronie); // określenie numeru tej podstrony

//wyświetlenie tablicy
for ($indeks=$i_min; $indeks<=$i_max && $indeks<$ilosc_wierszy; ++$indeks)
{
	echo $tablica[$indeks];
}

$stronka->OtworzBlok("stronicuj"); 

if ($strona_biezaca>1)
{
	echo '<a href="stronicuj.php?strona='.$start_poprzedniej.'"><<</a>' ;
}

$start_podstrony=1; //numer pierwszej podstrony utworzony jako licznik w pętli WHILE

while ($start_podstrony<=$ilosc_podstron)
{
	if ($start_podstrony==$ta_strona)
	{
		echo '&nbsp;<span style="font-weight:bold;color:red;">'.$start_podstrony.'</span> ' ;  
	}
	else
	{
		echo '&nbsp;<a href="stronicuj.php?strona='.$start_podstrony.'">'.$start_podstrony.'</a> ' ; 
	}
	++$start_podstrony;
}

if ($strona_biezaca<$ilosc_podstron)
{
	echo '<a href="stronicuj.php?strona='.$start_nastepnej.'">>></a>' ;
}
$stronka->DomknijBlok(); // stronicuj

if (!isset($wstaw_naglowek))
{
	$stronka->DomknijBlok(); // div tematy 
	$stronka->DomknijBlok(); // div tresc
	$stronka->WyswietlStopke();
}
?>

