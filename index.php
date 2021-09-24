<?php
require_once("classes/Strona.php");
$stronka=new Strona();
$stronka->tytul="Strona startowa";
$stronka->WyswietlNaglowek();
$stronka->OtworzBlok("tresc");
$stronka->OtworzBlok("srodek");
?>

<p style="margin-top:15%;";>Aby się zalogować kliknij <a href="zaloguj.php">tutaj</a><br /><br />
Aby założyć konto kliknij <a href="rejestracja.php">tutaj</a><br /><br />
Jeżeli zapomniałeś hasło wyślij <a href="mailto:ryszard@rkhost.strefa.pl">mail_do_administratora</a></p>

<?php
$stronka->DomknijBlok(); //srodek
$stronka->DomknijBlok(); //tresc
$stronka->WyswietlStopke();
?>

