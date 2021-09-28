<?php
session_start();
if ($_SESSION['zalogowany']!="tak") {
	echo "<html><body><script>window.location.href = 'zaloguj.php';</script></body></html>";
	exit;
}
require_once("classes/Strona.php");
$stronka=new Strona();
$stronka->tytul="Edycja wiadomości";
$stronka->WyswietlNaglowek();
$stronka->OtworzBlok("menugorne");
$stronka->WyswietlMenuGlowne();
$stronka->DomknijBlok(); //menugorne
$stronka->WyswietlPasekLogin();
$stronka->OtworzBlok("tresc");
$stronka->OtworzBlok("news");

$idnews=$_GET["idnews"];

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

$sql="SELECT temat, tresc FROM wiadomosci WHERE id_news=\"$idnews\"";
$result=$mysqli->query($sql);
if (!$result)
{
	echo 'Błąd zapytania SELECT.<br/>';
	$stronka->DomknijStrone();
	exit;
}
$wiersz=$result->fetch_object();
$tresc_wiadom=$wiersz->tresc; // treść wiadomości
$temat_wiadom=$wiersz->temat; // temat wiadomości
?>
<p>Edycja wiadomości</p>
<br /><br />
<form method="post" action="">
<table>
<tr><td>temat:</td><td><input type="text" name="temat" value="<?php echo $temat_wiadom; ?>" size="60" maxlength="150" /><br /><br /></td></tr>
<tr><td>treść:</td><td><textarea name="tresc" cols="70" rows="5"><?php echo $tresc_wiadom; ?></textarea></td></tr>
</table>
<br />
<p><input type="submit" name="submit" value="Zapisz zmiany" /></p>
</form>
<br/><br/>
<?php
if (isset($_POST["submit"]))
{
	$tresc=addslashes(htmlspecialchars(trim($_POST["tresc"])));
	$temat=addslashes(htmlspecialchars(trim($_POST["temat"])));

	if ((!$temat) || (!$tresc))
	{
		echo 'Proszę wypełnić wszystkie pola.<br />';
		$stronka->DomknijStrone();
		exit;
	}

	$sql='UPDATE wiadomosci SET tresc="'.$tresc.'", temat="'.$temat.'" WHERE id_news="'.$idnews.'"';
	$result=$mysqli->query($sql);
	if (!$result)
	{
		echo 'Błąd zapytania UPDATE.<br />';
		$stronka->DomknijStrone();
		exit;
	}
	echo 'Wiadomość zapisana';
}
$mysqli->close();
$stronka->DomknijBlok(); // div news
$stronka->DomknijBlok(); // div tresc
$stronka->WyswietlStopke();
?>
