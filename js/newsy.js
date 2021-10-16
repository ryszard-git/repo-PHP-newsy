function zapytaj(id_news,akcja)
{
	switch(akcja)
	{
		//1 - usunięcie
		case 1:
			let wynik = confirm("Usunąć tą wiadomość ?");
			if (wynik == true)
			{
				window.location.href = "usun_wiadomosc.php?idnews=" + id_news;
			}
		break;
		
		//0 - edycja
		case 0:
			window.location.href = "edytuj_wiadomosc.php?idnews=" + id_news;
		break;
		
		default:
			alert("Niewłaściwa opcja");
	}
}