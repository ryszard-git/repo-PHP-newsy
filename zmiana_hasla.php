<?php
session_start();
if ($_SESSION['zalogowany']!="tak") {
	echo "<html><body><script>window.location.href = 'zaloguj.php';</script></body></html>";
	exit;
}
require_once("classes/Strona.php");
$stronka=new Strona();
$stronka->tytul="Zmiana hasła";
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
	<tr><td>hasło bieżące:</td><td><input type="password" name="haslo" size="10" maxlength="25" /></td></tr>
	<tr><td>nowe hasło:</td><td><input type="password" name="nowe_haslo" size="10" maxlength="25" /></td></tr>
	<tr><td>powtórz nowe hasło:</td><td><input type="password" name="powt_nowe_haslo" size="10" maxlength="25" /></td></tr>
	</table>
	<br />
	<p><input type="submit" name="submit" value="Zmień hasło" /></p>
	</form>
	<br/><br/>
<?php
if (isset($_POST['submit'])) {
	$login_name=$_SESSION['login_name']; // $login_name jest przepuszczone przez funkcję 'addslashes' w pliku 'zaloguj.php'.

	if ((!$login_name) || (!$_POST['haslo']) || (!$_POST['nowe_haslo']) || (!$_POST['powt_nowe_haslo'])) {
		echo "Prosze wypełnić wszystkie pola formularza<br/>";
		echo 'Żadne z haseł nie może być puste.<br/>';
		$stronka->DomknijStrone();
		exit;
	}

	$haslo=sha1(trim($_POST["haslo"]));
	$nowe_haslo=sha1(trim($_POST["nowe_haslo"]));
	$powt_nowe_haslo=sha1(trim($_POST["powt_nowe_haslo"]));

	if ($nowe_haslo==$powt_nowe_haslo) {
		echo "Hasło i powtórzenie hasła zgodne<br/>";
	} else {
		echo "Hasło i powtórzenie hasła niezgodne<br/>";
		$stronka->DomknijStrone();
		exit;
	}

	if (strlen($_POST["nowe_haslo"])<5)
	{
		echo 'Hasło powinno mieć conajmniej 5 znaków.<br />Spróbuj ponownie.<br />';
		$stronka->DomknijStrone();
		exit;
	}
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

	// szukanie loginu, któremu odpowiadające hasło ma być zmienione
	$sql="SELECT login, haslo FROM uzytkownicy WHERE login=\"$login_name\" AND haslo=\"$haslo\"";
	$result=$mysqli->query($sql);

	if (!$result) {
		echo "Błąd zapytania login - hasło.<br/>";
		$stronka->DomknijStrone();
		exit;
	}
	$ilosc=$result->num_rows;

	if ($ilosc === 1) {
		echo "Konto zostało znalezione<br/>";
		$sql="UPDATE uzytkownicy SET haslo=\"$nowe_haslo\" WHERE login=\"$login_name\"";
		$result=$mysqli->query($sql);
		if (!$result) {
			echo "Błąd zapytania do bazy - wpisanie nowego hasła.<br/>";
			$stronka->DomknijStrone();
			exit;
		}
		echo "Hasło zostało zmienione<br/>";
	} else {
		echo "Niezgodność loginu i hasła dotychczasowego<br/>";
		$stronka->DomknijStrone();
		exit;
		}
	$mysqli->close();
} // if isset

$stronka->DomknijBlok(); // div srodek
$stronka->DomknijBlok(); // div tresc
$stronka->WyswietlStopke();
?>
