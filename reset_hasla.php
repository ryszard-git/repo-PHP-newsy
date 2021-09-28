<?php
session_start();
if ($_SESSION['zalogowany']!="tak") {
	echo "<html><body><script>window.location.href = 'zaloguj.php';</script></body></html>";
	exit;
}
require_once("classes/Strona.php");
$stronka=new Strona();
$stronka->tytul="Resetowanie hasła";
$stronka->WyswietlNaglowek();
$stronka->OtworzBlok("menugorne");
$stronka->WyswietlMenuGlowne();
$stronka->DomknijBlok(); //menugorne
$stronka->WyswietlPasekLogin();
$stronka->OtworzBlok("tresc");
$stronka->OtworzBlok("srodek");
?>
<form method="post" action="">
<table>
<span>Wpisz login użytkownika, któremu chcesz zresetować hasło oraz nowe hasło dla tego użytkownika.</span><br /><br />
<tr><td>login:</td><td><input type="text" name="login_name" size="15" maxlength="25" /></td></tr>
<tr><td>nowe hasło:</td><td><input type="password" name="nowe_haslo" size="15" maxlength="25" /></td></tr>
</table>
<br />
<p><input type="submit" name="submit" value="Ustaw hasło"/></p>
</form>
<br/><br/>
<?php
if (isset($_POST["submit"]))
{
	$login_name=addslashes(trim($_POST["login_name"]));
	$nowe_haslo=$_POST["nowe_haslo"];

	if ((!$login_name) || (!$nowe_haslo)) {
		echo "Prosze wypełnić wszystkie pola formularza";
		$stronka->DomknijStrone();
		exit;
	}
	if (strlen($_POST["nowe_haslo"])<5)
	{
		echo 'Hasło powinno mieć conajmniej 5 znaków. Spróbuj ponownie.<br />';
		$stronka->DomknijStrone();
		exit;
	}
	$nowe_haslo=sha1(trim($_POST["nowe_haslo"]));
	require "config/config.php";
	@ $mysqli = new mysqli($host,$db_user,$db_passwd,$db_name);
	if ($mysqli->connect_error) {
		echo 'Próba połączenia z bazą nie powiodła się.<br/>';
		echo $mysqli->connect_error;
		$stronka->DomknijStrone();
		exit;
	}
	$result=$mysqli->set_charset("utf8");
	if (!$result) {
		echo "Błąd ustawienia kodowania.<br/>";
		$stronka->DomknijStrone();
		exit;
	}
	$sql="SELECT login FROM uzytkownicy WHERE login=\"$login_name\"";
	$result=$mysqli->query($sql);
	if (!$result) {
		echo "Błąd zapytania login.<br/>";
		$stronka->DomknijStrone();
		exit;
	}
	$ilosc=$result->num_rows;

	if ($ilosc === 1) {
		echo "Konto zostało znalezione<br/>";
		$sql="UPDATE uzytkownicy SET haslo=\"$nowe_haslo\" WHERE login=\"$login_name\"";
		$result=$mysqli->query($sql);
		if (!$result) {
			echo "Błąd zapytania UPDATE.<br/>";
			$stronka->DomknijStrone();
			exit;
	}
		echo "Hasło zostało ustawione<br/>";
	} else {
		echo "Konto nie zostało znalezione<br/>";
		$stronka->DomknijStrone();
		exit;
	}

	$mysqli->close();
} // if isset
$stronka->DomknijBlok(); // div srodek
$stronka->DomknijBlok(); // div tresc
$stronka->WyswietlStopke();
?>
