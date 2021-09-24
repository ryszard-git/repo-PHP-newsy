<?php
require_once("classes/Strona.php");
$stronka=new Strona();
$stronka->tytul="Rejestracja użytkownika";
$stronka->WyswietlNaglowek();
$stronka->OtworzBlok("menugorne");
$stronka->WyswietlMenu($stronka->menu_startowe);
$stronka->DomknijBlok(); //menugorne
$stronka->OtworzBlok("tresc");
$stronka->OtworzBlok("srodek");
?>
	<p>Rejestracja</p>
	<br />
	<form method="post" action="">
	<table>
	<tr><td>login:</td><td><input type="text" name="login_name" size="15" maxlength="25" /></td></tr>
	<tr><td>hasło:</td><td><input type="password" name="haslo" size="15" maxlength="25" /></td></tr>
	<tr><td>powtórz hasło:</td><td><input type="password" name="haslo_powt" size="15" maxlength="25" /></td></tr>
	<tr><td>imię:</td><td><input type="text" name="imie" size="15" maxlength="30" /></td></tr>
	<tr><td>nazwisko:</td><td><input type="text" name="nazwisko" size="15" maxlength="50" /></td></tr>
	</table>
	<br /><br />
	<p><input type="submit" name="submit" value="Załóż konto" /></p>
	</form>
	<br/><br/>
<?php
if (isset($_POST['submit'])) {
	$login_name=addslashes(trim($_POST["login_name"]));
	$haslo=$_POST["haslo"];
	$haslo_powt=$_POST["haslo_powt"];
	$imie=addslashes(trim($_POST["imie"]));
	$nazwisko=addslashes(trim($_POST["nazwisko"]));
	if ((!$login_name) || (!$haslo) || (!$haslo_powt) || (!$imie) || (!$nazwisko))
	{
		echo "Proszę wypełnić wszystkie pola !!!";
		$stronka->DomknijStrone();
		exit;
	}
	if ($haslo!=$haslo_powt)
	{
		echo 'Hasło i powtórzenie hasła niezgodne !!!';
		$stronka->DomknijStrone();
		exit;
	}

	if (strlen($_POST["haslo"])<5)
	{
		echo 'Hasło powinno mieć conajmniej 5 znaków.<br />Spróbuj ponownie.<br />';
		$stronka->DomknijStrone();
		exit;
	}

	$haslo=sha1(trim($_POST["haslo"]));
	unset ($haslo_powt);

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

	// sprawdzenie czy istnieje konto o podanej nazwie. Gdy $ilosc=0 to nie istnieje
	$sql="SELECT login FROM uzytkownicy WHERE login=\"$login_name\"";
	$result=$mysqli->query($sql);
	if (!$result) {
		echo "Błąd zapytania o login.<br/>";
		$stronka->DomknijStrone();
		exit;
	}
	$ilosc=$result->num_rows;
	if ($ilosc === 0) {
		$czy_admin="NIE";
		$sql="INSERT INTO uzytkownicy (login, haslo, czy_admin, imie, nazwisko)
		VALUES (\"$login_name\", \"$haslo\", \"$czy_admin\", \"$imie\", \"$nazwisko\")";
		$result=$mysqli->query($sql);
		if (!$result) {
			echo "Błąd zapytania INSERT.<br/>";
			$stronka->DomknijStrone();
			exit;
		}
		echo 'Konto zostało założone<br />';
	} else {
		echo "Konto o podanej nazwie już istnieje<br/>";
		$stronka->DomknijStrone();
		exit;
	}

	$mysqli->close();
} // if isset
$stronka->DomknijBlok(); // div srodek
$stronka->DomknijBlok(); // div tresc
$stronka->WyswietlStopke();
?>
