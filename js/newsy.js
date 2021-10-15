function zapytaj(id_news)
{
	let wynik = confirm("Usunąć tą wiadomość ?");
	if (wynik == true)
	{
		window.location.href = "usun_wiadomosc.php?idnews=" + id_news;
	}
}

function edycja(id_news)
{
	window.location.href = "edytuj_wiadomosc.php?idnews=" + id_news;
}