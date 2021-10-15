<?php
class Strona
{
public $tytul;

public $menu_startowe=array("Strona startowa" => "index.php");

public $menu_usera=array("Strona główna" => "glowna.php",
			"Dodawanie wiadomości" => "dodaj_wiadomosc.php",
			"Przeglądanie wiadomości" => "przeglad_wiadomosci.php",
			"Zmiana hasła" => "zmiana_hasla.php");

public $menu_admina=array("Strona główna" => "glowna.php",
			"Przeglądanie wiadomości" => "przeglad_wiadomosci.php",
			"Resetowanie hasła" => "reset_hasla.php",
			"Zmiana hasła" => "zmiana_hasla.php",
			"Dodawanie użytkownika" => "dodaj_uzytkownika.php");

public function DomknijStrone()
{
// funkcja zapewnia poprawne wyświetlenie szablonu strony po przerwaniu skryptu funkcją exit
	$this->DomknijBlok();
	$this->DomknijBlok();
	$this->WyswietlStopke();
}

public function DomknijStrone1()
{
// funkcja zapewnia poprawne wyświetlenie szablonu strony po przerwaniu skryptu funkcją exit
	$this->DomknijBlok();
	$this->WyswietlStopke();
}

public function OtworzBlok($blok)
{
	echo '<div class="'.$blok.'">';
}

public function WyswietlNaglowek()
{
?>
<!DOCTYPE html>
<html lang="pl">    
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="css/newsy.css">
<script src="js/newsy.js" defer></script>
<title><?php echo $this->tytul; ?></title>
</head>
<body>
<div class="all">
	<div class="baner">
		<p>System zarządzania wiadomościami</p>
	</div>

<?php
}

public function WyswietlMenuGlowne()
{
if ($_SESSION["czy_admin"]==="TAK")
{
	$this->WyswietlMenu($this->menu_admina);
}
if ($_SESSION["czy_admin"]==="NIE")
{
	$this->WyswietlMenu($this->menu_usera);
}
}

public function WyswietlPasekLogin()
{
	$this->OtworzBlok("paseklogin");
	echo 'Zalogowany: <strong>'.$_SESSION["login_name"].'</strong> &nbsp;&nbsp; [ <a href="wyloguj.php">Wyloguj</a> ] &nbsp;&nbsp;';
	$this->DomknijBlok();
}

public function WyswietlMenu($przyciski)
{
	echo '<ul>';
	foreach ($przyciski as $nazwa=>$url)
	{
		$this->WyswietlPrzycisk($nazwa,$url,!$this->CzyToAktualnyURL($url));
	}
	echo '</ul>';
}

public function CzyToAktualnyURL($url)
{
	if (strpos($_SERVER['PHP_SELF'],$url)==false)
	{
		return false;
	}
	else
	{
		return true;
	}
}

public function WyswietlPrzycisk($nazwa,$url,$aktywny=true)
{
	if ($aktywny)
	{
		echo '<li><a href="'.$url.'">'.$nazwa.'</a></li>';
	}
	else
	{
		echo '<li><span class="przycisk">'.$nazwa.'</span></li>';
	}
}

public function DomknijBlok()
{
	echo '</div>';
}

public function WyswietlFormWyszukiwania()
{
?>
<p>Wyszukaj frazę w:</p>
<br />
<form method="post" action="">
<p class="selekcja"><input type="radio" name="wyszukaj" value="temat" checked="checked" /> - temacie</p>
<p class="selekcja"><input type="radio" name="wyszukaj" value="tresc" /> - treści</p>
<br />
<input type="text" name="fraza" size="10" maxlength="40" />
<br /><br />
<input type="submit" name="submit" value="Wyszukaj" />
</form>
<?php
}

public function Stronicuj($tablica)
{
	$wstaw_naglowek='abc'; // zmienna wskaźnikowa - pomocna przy sterowaniu wyświetlaniem nagłówka, menu oraz stopki w skrypcie stronicującym wyniki
	require "stronicuj.php";
}

public function WyswietlStopke()
{
?>
<div class="stopka">
	&copy; 2012 - 2021 Wszystkie prawa zastrzeżone<br/>
	<a href="mailto:ryszard@rkhost.strefa.pl">e-mail do administratora aplikacji</a>
</div>
</div> <!-- div all -->
</body>
</html>
<?php
}
}
?>

