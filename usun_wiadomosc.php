<?php
session_start();
if ($_SESSION['zalogowany']!="tak") {
	echo "<html><body><script>window.location.href = 'zaloguj.php';</script></body></html>";
	exit;
}
echo '<html><body>';
$idnews=$_GET["idnews"];
require "config/config.php";

@ $mysqli = new mysqli($host,$db_user,$db_passwd,$db_name);
if ($mysqli->connect_error)
{
	echo 'Próba połączenia z bazą nie powiodła się.<br/>';
	echo $mysqli->connect_error;
	exit;
}
$sql="DELETE FROM wiadomosci WHERE id_news=\"$idnews\"";
$result=$mysqli->query($sql);
if (!$result)
{
	echo 'Błąd zapytania DELETE.<br/>';
	exit;
}
$mysqli->close();

echo "<script>window.location.href = 'przeglad_wiadomosci.php';</script>";
echo '</body></html>';
?>

