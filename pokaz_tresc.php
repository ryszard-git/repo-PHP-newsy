<?php
session_start();
if ($_SESSION['zalogowany']!="tak") {
	echo "<script>window.location.href = 'zaloguj.php';</script>";
	exit;
}
require_once("classes/Strona.php");
$stronka=new Strona();
$stronka->tytul="Przeglądanie treści wiadomości";
$stronka->WyswietlNaglowek();
$stronka->OtworzBlok("menugorne");
$stronka->WyswietlMenuGlowne();
$stronka->DomknijBlok(); //menugorne
$stronka->WyswietlPasekLogin();
$stronka->OtworzBlok("tresc");

echo '<p style="text-align:center;">Przeglądanie treści wiadomości</p><br /><br />';
$idnews=$_GET['idnews'];
require "config/config.php";

@ $mysqli = new mysqli($host,$db_user,$db_passwd,$db_name);
if ($mysqli->connect_error)
{
	echo 'Próba połączenia z bazą nie powiodła się.<br/>';
	echo $mysqli->connect_error;
	$stronka->DomknijStrone1();
	exit;
}
$result=$mysqli->set_charset("utf8");
if (!$result)
{
	echo 'Błąd ustawienia kodowania.<br/>';
	$stronka->DomknijStrone1();
	exit;
}

$sql="SELECT uzytkownicy.imie, uzytkownicy.nazwisko, uzytkownicy.login, wiadomosci.data, wiadomosci.temat, wiadomosci.tresc FROM uzytkownicy, wiadomosci 
WHERE wiadomosci.id_news=\"$idnews\" AND uzytkownicy.id_user=wiadomosci.id_user";
$result=$mysqli->query($sql);
if (!$result)
{
	echo 'Błąd zapytania SELECT.<br/>';
	$stronka->DomknijStrone1();
	exit;
}

$wiersz=$result->fetch_object();

$imie=stripslashes($wiersz->imie);
$nazwisko=stripslashes($wiersz->nazwisko);
$login=$wiersz->login;
$temat=stripslashes($wiersz->temat);
$data=$wiersz->data;
$tresc=stripslashes($wiersz->tresc);

echo '<strong>'.$temat.'</strong><br /><br />';
echo $tresc.'<br /><br /><hr /><br />';

if ($_SESSION["czy_admin"]==="TAK")
{
	echo '<a title="Usuń tą wiadomość" href="usun_wiadomosc.php?idnews='.$idnews.'">Usuń</a>&nbsp;&nbsp;&nbsp;';
}
if ($login===$_SESSION["login_name"])
{
	echo '<a title="Edytuj tą wiadomość" href="edytuj_wiadomosc.php?idnews='.$idnews.'">Edytuj</a>&nbsp;&nbsp;';
	echo '<a style="float:right;" title="Usuń tą wiadomość" href="usun_wiadomosc.php?idnews='.$idnews.'">Usuń</a>&nbsp;&nbsp;&nbsp;';
}
echo '<span style="font-style:italic; font-size:10px;">Wiadomość dodano: '.$data.' przez: '.$login.' ('.$imie.' '.$nazwisko.')</span><br /><br /><hr />';
$mysqli->close();
$stronka->DomknijBlok(); // div tresc
$stronka->WyswietlStopke();
?>
