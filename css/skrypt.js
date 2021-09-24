function akceptuj()
{
	var wynik = confirm("Potwierdź operację");
	if(wynik)
	{
//		document.write("<?php $this->DomknijStrone(); ?>");
//		document.write("<?php session_start(); ?>");
//		document.write("<?php $_SESSION['kasuj']='nie'; ?>");
		document.write("OK");
	}
	else
	{
		document.write("<?php session_start(); ?>");
		document.write("<?php $_SESSION['kasuj']='nie'; ?>");
//		document.write("<?php echo \"$_SESSION['kasuj']\"; ?>");
//		document.write("<?php exit('WYJSCIE'); ?>");

	}
}

