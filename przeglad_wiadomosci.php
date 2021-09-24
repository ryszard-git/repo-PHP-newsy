<?php
session_start();
if ($_SESSION['zalogowany']!="tak") {
	echo "<script>window.location.href = 'zaloguj.php';</script>";
	exit;
}
require_once("classes/Strona.php");
$stronka=new Strona();
$stronka->tytul="Przeglądanie wiadomości";
$stronka->WyswietlNaglowek();
$stronka->OtworzBlok("menugorne");
$stronka->WyswietlMenuGlowne();
$stronka->DomknijBlok(); //menugorne
$stronka->WyswietlPasekLogin();
$stronka->OtworzBlok("tresc");

$stronka->OtworzBlok("form"); // otwarcie bloku zawierającego formularz wyszukiwania
$stronka->WyswietlFormWyszukiwania();
$stronka->DomknijBlok(); //form

$stronka->OtworzBlok("tematy");
echo '<p style="text-align:center;">Przeglądanie wiadomości</p><br /><br />';

require "config/config.php";

@ $mysqli = new mysqli($host,$db_user,$db_passwd,$db_name);
if ($mysqli->connect_error)
{
	echo 'Próba połączenia z bazą nie powiodła się.<br/>';
	echo $mysqli->connect_error;
	$stronka->DomknijStrone();
	exit;
}
$result=$mysqli->set_charset("utf8");
if (!$result)
{
	echo 'Błąd ustawienia kodowania.<br/>';
	$stronka->DomknijStrone();
	exit;
}

	$login_usera=$_SESSION['login_name']; // nazwa aktualnie zalogowanego użytkownika
if (isset($_POST['submit']))
{
	$wyszukaj=$_POST['wyszukaj']; // zmienna zawiera obszar poszukiwań (treść lub temat wiadomości)
	$fraza=addslashes(htmlspecialchars(trim($_POST['fraza']))); // zmienna zawiera szukany ciąg znaków

	if ($_SESSION['czy_admin']==='NIE')
	{
//		$sql="SELECT id_news, temat FROM wiadomosci WHERE ".$wyszukaj." LIKE '%".$fraza."%' ORDER BY data DESC"; 
		$sql='SELECT uzytkownicy.id_user, uzytkownicy.login, wiadomosci.id_user, wiadomosci.id_news, wiadomosci.temat FROM uzytkownicy, wiadomosci WHERE uzytkownicy.login="'.$login_usera.'" AND wiadomosci.id_user=uzytkownicy.id_user AND '.$wyszukaj." LIKE '%".$fraza."%' ORDER BY data DESC ";
	}
	elseif ($_SESSION['czy_admin']==='TAK')
	{
		$sql="SELECT id_news, temat FROM wiadomosci WHERE ".$wyszukaj." LIKE '%".$fraza."%' ORDER BY data DESC";
	}
}
else
{
	if ($_SESSION['czy_admin']==='NIE')
	{
		$sql='SELECT uzytkownicy.id_user, uzytkownicy.login, wiadomosci.id_user, wiadomosci.id_news, wiadomosci.temat FROM uzytkownicy, wiadomosci WHERE uzytkownicy.login="'.$login_usera.'" AND wiadomosci.id_user=uzytkownicy.id_user ORDER BY data DESC ';
	}
	elseif ($_SESSION['czy_admin']==='TAK')
	{
		$sql='SELECT id_news, temat FROM wiadomosci ORDER BY data DESC';
	}

}
$result=$mysqli->query($sql);
if (!$result)
{
	echo 'Błąd zapytania SELECT.<br/>';
	$stronka->DomknijStrone();
	exit;
}
$ile_znaleziono=$result->num_rows;
if ($ile_znaleziono===0)
{
	echo '<span style="font-style:italic;">Brak danych pasujących do wzorca.</span><br />';
}
else
{
	while ($wiersz=$result->fetch_object())
	{
		$temat=stripslashes($wiersz->temat);
		$id_news=$wiersz->id_news;
		$tablica[]='<a href="pokaz_tresc.php?idnews='.$id_news.'">'.$temat.'</a><br /><br />';
	}
}
if (isset($tablica))
{
	$_SESSION['tablica']=$tablica;
	$stronka->Stronicuj($tablica);
}

$mysqli->close();
$stronka->DomknijBlok(); // div tematy 
$stronka->DomknijBlok(); // div tresc
$stronka->WyswietlStopke();
?>
