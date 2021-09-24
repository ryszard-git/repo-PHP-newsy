<?php
session_start();
if ($_SESSION['zalogowany']!="tak") {
	echo "<script>window.location.href = 'zaloguj.php';</script>";
	exit;
}
require_once("classes/Strona.php");
$stronka=new Strona();
$stronka->tytul="Dodawanie wiadomości";
$stronka->WyswietlNaglowek();
$stronka->OtworzBlok("menugorne");
$stronka->WyswietlMenuGlowne();
$stronka->DomknijBlok(); //menugorne
$stronka->WyswietlPasekLogin();
$stronka->OtworzBlok("tresc");
$stronka->OtworzBlok("news");
?>
	<form method="post" action="">
	<table>
	<tr><td>temat:<br /><br /></td><td><input type="text" name="temat" size="60" maxlength="150" /><br /><br /></td></tr>
	<tr><td>tresc:</td><td><textarea name="tresc" cols="70" rows="5"></textarea></td></tr>
	</table>
	<br />
	<p><input type="submit" name="submit" value="Dodaj wiadomość" /></p>
	</form>
	<br/><br/>
<?php
if (isset($_POST['submit']))
{
	$temat=addslashes(htmlspecialchars(trim($_POST["temat"])));
	$tresc=addslashes(htmlspecialchars(trim($_POST["tresc"])));
	if ((!$temat) || (!$tresc))
	{
		echo 'Proszę wypełnić wszystkie pola.<br />';
		$stronka->DomknijStrone();
		exit;
	}

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
		echo "Błąd ustawienia kodowania.<br/>";
		$stronka->DomknijStrone();
		exit;
	}

	// zapytanie w celu ustalenia ID_usera na podstawie LOGIN_NAME
	$login_name=$_SESSION['login_name'];
	$sql="SELECT id_user FROM uzytkownicy WHERE login=\"$login_name\"";
	$result=$mysqli->query($sql);
	if (!$result)
	{
		echo "Błąd zapytania SELECT.<br/>";
		$stronka->DomknijStrone();
		exit;
	}
	$wiersz=$result->fetch_object();
	$iduser=$wiersz->id_user; // id usera zapisującego wiadomość
	$data_wpisu=date('Y-m-d H:i:s');

	$sql="INSERT INTO wiadomosci (id_user, data, temat, tresc) VALUES 
	(\"$iduser\", \"$data_wpisu\", \"$temat\", \"$tresc\")";

	$result=$mysqli->query($sql);
	if (!$result)
	{
		echo "Błąd zapytania INSERT.<br/>";
		$stronka->DomknijStrone();
		exit;
	}
	echo 'Wiadomość zapisana.<br />';
	$mysqli->close();
} // if isset
$stronka->DomknijBlok(); // div news
$stronka->DomknijBlok(); // div tresc
$stronka->WyswietlStopke();
?>
