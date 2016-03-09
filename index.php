
<html lang="pl">
<?php
			error_reporting(E_ALL ^ E_ALL);
			setlocale(LC_ALL, 'pl_PL', 'pl', 'Polish_Poland.28592');
			$polaczenie = mysql_connect('serwer1692022.home.pl', '19575369_0000002', 'GvW:62vvsTC0') or die("Brak połączenia: " . mysql_error());
            $baza = mysql_select_db('19575369_0000002',$polaczenie) or exit("Nie wybrano bazy, błąd: " . mysql_error());
            mysql_query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");    
?>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title>Witam w panelu administracyjnym</title>
<script src="./ckeditor/ckeditor.js"></script>
<link rel="Stylesheet" type="text/css" href="css/style.css" />
</head>
<body>
 
<div id="container">
 

 
<!-- sidebar -->
<aside>
<nav>
<dl>
<dd><a href=index.php>Strona główna</a></dd>
<dd><a href="index.php?id=dodaj_pozycje">Dodaj pozycję do menu</a></dd>
<dd><a href="index.php?id=zmien_logo">Zmień logo strony</a></dd>
<dd><a href="index.php?id=dodaj_plik">Wyślij plik</a></dd>
<?php
	
			$uslugi = "SELECT * FROM menu";
            $uslugi_res = mysql_query($uslugi,$polaczenie) or die ('Błąd: ' . mysql_error());
						      if(!$uslugi_res) echo "Błąd bazy danych"; else
		    			      while($u = mysql_fetch_assoc($uslugi_res))
		    			      {
									
									echo "<dd><a href=index.php?id=$u[url]>$u[pozycja]</a></dd>";
									
		    			      }


?>
</dl>

</nav>
</aside>
 <!-- header -->
<header>

</header>
<!-- main -->
<section id="main">
<?php
	
switch($_GET['id'])

{
   case 'dodaj_plik':
$plik_tmp = $_FILES['file']['tmp_name'];
$plik_nazwa = $_FILES['file']['name'];
$plik_rozmiar = $_FILES['file']['size'];

if(is_uploaded_file($plik_tmp)) {
     move_uploaded_file($plik_tmp, "./../download/$plik_nazwa");
    echo "Plik: <strong>$plik_nazwa</strong> o rozmiarze 
    <strong>$plik_rozmiar bajtów</strong> został przesłany na serwer!";                            
    chmod("./../download/$plik_nazwa", 0777); }
   echo"<div id=blok><form action=index.php?id=dodaj_plik method=POST ENCTYPE=multipart/form-data>
        <input type=file name=plik/><br/>
        <input type=submit value=Wyślij plik/></div>";
   break;
   case 'dodaj_pozycje':
if ($_POST['dodaj']) {

	  $nazwa =trim($_POST['nazwa']);
	  $url = str_ireplace(' ','_',trim($_POST['opis']));
	  $zapytanie = "INSERT INTO menu (pozycja,podmenu,artykul,url) VALUES ('$nazwa','N','<p>Tutaj należy wpisać artykuł</p>','$url')";
	  $wstaw = mysql_query($zapytanie,$polaczenie);
		
		if ($wstaw)
			echo "<p>Dodano nową pozycję menu</p>";
		else
			echo "<p>Dodanie nowej pozycji menu nie powiodło się</p>";
}
			?>
			  <div id=blok>
			  <form action="index.php?id=dodaj_pozycje" method="POST">
			  <p>Nazwa:</p><br /> 
	          <input type=text size=40 name=nazwa required /><br />
			  <p>Krótki opis:</p><br /> 
			  <input type=text size=40 name=opis required /><br /> 
              <input type="submit" name="dodaj" value="Dodaj" />
              <input type="reset" value="Resetuj" />
			  </div>
			<?php
         break;
case 'usun_podmenu':
	  $zapytanie = "DELETE FROM  podmenu WHERE id=$_GET[num]";
	  $wstaw = mysql_query($zapytanie,$polaczenie);
	  	
		if ($wstaw)
			echo "<p>Usunięto pozycję podmenu</p>";
		else
			echo "<p>Usuwanie podmenu nie powiodło się</p>";
	    $num = 0;
								 	$pmenu = "SELECT * FROM podmenu WHERE id_menu=$_GET[num]";
                                    $pmenu_res = mysql_query($pmenu,$polaczenie) or die ('Błąd: ' . mysql_error());
								    if(!$pmenu_res) echo "Błąd bazy danych"; else
		    			            while($p = mysql_fetch_assoc($pmenu_res))
									{
									  $num++;
									}
			if($num==0){
			$query = "UPDATE menu SET podmenu = 'N' WHERE id = '$_GET[num]'";
			$wstaw = mysql_query($query,$polaczenie);
			if ($wstaw)
			echo "<p>Aktualizacja menu powiodła się.</p>";
		else
			echo "<p>Aktualizacja menu nie powiodła się!</p>";
			}
			break;
case 'zmien_opis_menu':
if ($_POST['zmien_opis_menu1']) {
	        $opis= str_ireplace(' ','_',trim($_POST['nazwa']));
			$query = "UPDATE menu SET url = '$opis' WHERE id = '$_POST[ktory]'";
			$wstaw = mysql_query($query,$polaczenie);
			if ($wstaw)
			echo "<p>Aktualizacja opisu powiodła się.</p>";
		else
			echo "<p>Aktualizacja opisu nie powiodła się!</p>";			
						       }else {
								 	$pmenu = "SELECT * FROM menu WHERE id='$_GET[co]'";
                                    $pmenu_res = mysql_query($pmenu,$polaczenie) or die ('Błąd: ' . mysql_error());
								    if(!$pmenu_res) echo "Błąd bazy danych"; else {
		    			            $p = mysql_fetch_assoc($pmenu_res);
									echo "Nazwa: $p[pozycja]";
						      echo "<div id=blok>
									<form action=index.php?id=zmien_opis_menu method=POST>
									<p>Krótki opis:</p><br /> 
									<input type=text size=40 name=nazwa required value=$p[url] /><br />
									<input type=hidden name=ktory value=$_GET[co] />
									<input type=submit name=zmien_opis_menu1 value=Zmień />
									<input type=reset value=Resetuj />
									</div>"; }
							         }

			break;
case 'zmien_nazwe_menu':
if ($_POST['zmien_nazwe_menu1']) {
			$query = "UPDATE menu SET pozycja = '$_POST[nazwa]' WHERE id = '$_POST[ktory]'";
			$wstaw = mysql_query($query,$polaczenie);
			if ($wstaw)
			echo "<p>Aktualizacja nazwy powiodła się.</p>";
		else
			echo "<p>Aktualizacja nazwy nie powiodła się!</p>";			
						       }else {
								 	$pmenu = "SELECT * FROM menu WHERE id='$_GET[co]'";
                                    $pmenu_res = mysql_query($pmenu,$polaczenie) or die ('Błąd: ' . mysql_error());
								    if(!$pmenu_res) echo "Błąd bazy danych"; else {
		    			            $p = mysql_fetch_assoc($pmenu_res);
									echo "Nazwa: $p[pozycja]";
						      echo "<div id=blok>
									<form action=index.php?id=zmien_nazwe_menu method=POST>
									<p>Nazwa:</p><br /> 
									<input type=text size=40 name=nazwa required value=$p[pozycja] /><br />
									<input type=hidden name=ktory value=$_GET[co] />
									<input type=submit name=zmien_nazwe_menu1 value=Zmień />
									<input type=reset value=Resetuj />
									</div>"; }
							         }

			break;
case 'zmien_nazwe_podmenu':
if ($_POST['zmien_nazwe_podmenu1']) {
			$query = "UPDATE podmenu SET nazwa = '$_POST[nazwa]' WHERE id = '$_POST[ktory]'";
			$wstaw = mysql_query($query,$polaczenie);
			if ($wstaw)
			echo "<p>Aktualizacja nazwy powiodła się.</p>";
		else
			echo "<p>Aktualizacja nazwy nie powiodła się!</p>";			
						       }else {
								 	$pmenu = "SELECT * FROM podmenu WHERE id='$_GET[jaki]'";
                                    $pmenu_res = mysql_query($pmenu,$polaczenie) or die ('Błąd: ' . mysql_error());
								    if(!$pmenu_res) echo "Błąd bazy danych"; else {
		    			            $p = mysql_fetch_assoc($pmenu_res);
									echo "Nazwa: $p[nazwa]";
						      echo "<div id=blok>
									<form action=index.php?id=zmien_nazwe_podmenu method=POST>
									<p>Nazwa:</p><br /> 
									<input type=text size=40 name=nazwa required value=$p[nazwa] /><br />
									<input type=hidden name=ktory value=$_GET[jaki] />
									<input type=submit name=zmien_nazwe_podmenu1 value=Zmień />
									<input type=reset value=Resetuj />
									</div>"; }
							         }

			break;
case 'zmien_opis_podmenu':
if ($_POST['zmien_opis_podmenu1']) {
			$opis= str_ireplace(' ','_',trim($_POST['nazwa']));
			$query = "UPDATE podmenu SET url = '$opis' WHERE id = '$_POST[ktory]'";
			$wstaw = mysql_query($query,$polaczenie);
			if ($wstaw)
			echo "<p>Aktualizacja opisu powiodła się.</p>";
		else
			echo "<p>Aktualizacja opisu nie powiodła się!</p>";			
						       }else {
								 	$pmenu = "SELECT * FROM podmenu WHERE id='$_GET[jaki]'";
                                    $pmenu_res = mysql_query($pmenu,$polaczenie) or die ('Błąd: ' . mysql_error());
								    if(!$pmenu_res) echo "Błąd bazy danych"; else {
		    			            $p = mysql_fetch_assoc($pmenu_res);
									echo "Opis: $p[url]";
						      echo "<div id=blok>
									<form action=index.php?id=zmien_opis_podmenu method=POST>
									<p>Nazwa:</p><br /> 
									<input type=text size=40 name=nazwa required value=$p[url] /><br />
									<input type=hidden name=ktory value=$_GET[jaki] />
									<input type=submit name=zmien_opis_podmenu1 value=Zmień />
									<input type=reset value=Resetuj />
									</div>"; }
							         }

			break;
case 'zmien_artykul_menu':
if ($_POST['zmien_artykul_menu1']) {
			$query = "UPDATE menu SET artykul = '$_POST[tresc]' WHERE id = '$_POST[ktory]'";
			$wstaw = mysql_query($query,$polaczenie);
			if ($wstaw)
			echo "<p>Aktualizacja artykułu powiodła się.</p>";
		else
			echo "<p>Aktualizacja artykułu nie powiodła się!</p>";			
						       }else {
								 	$pmenu = "SELECT * FROM menu WHERE id='$_GET[co]'";
                                    $pmenu_res = mysql_query($pmenu,$polaczenie) or die ('Błąd: ' . mysql_error());
								    if(!$pmenu_res) echo "Błąd bazy danych"; else {
		    			            $p = mysql_fetch_assoc($pmenu_res);
									echo "Nazwa: $p[pozycja]";
						      echo "<div id=blok>
									<form action=index.php?id=zmien_artykul_menu method=POST>
									<textarea class=ckeditor id=editor9  style=width:90; height: 100%; name=tresc lang=pl>$p[artykul]</textarea>
									<input type=hidden name=ktory value=$_GET[co] />
									<input type=submit name=zmien_artykul_menu1 value=Zmień />
									<input type=reset value=Resetuj />
									</div>"; }
							         }

			break;
case 'zmien_artykul_podmenu':
if ($_POST['zmien_artykul_podmenu']) {
			
			$query = "UPDATE podmenu SET artykul = '$_POST[tresc]' WHERE id = '$_POST[ktory]'";
			$wstaw = mysql_query($query,$polaczenie);
			if ($wstaw)
			echo "<p>Aktualizacja opisu powiodła się.</p>";
		else
			echo "<p>Aktualizacja opisu nie powiodła się!</p>";			
						       } else {

								 	$pmenu = "SELECT * FROM podmenu WHERE id='$_GET[jaki]'";
                                    $pmenu_res = mysql_query($pmenu,$polaczenie) or die ('Błąd: ' . mysql_error());
								    if(!$pmenu_res) echo "Błąd bazy danych"; else {
		    			            $p = mysql_fetch_assoc($pmenu_res);
									echo "Nazwa: $p[nazwa]";
						      echo "<div id=blok>
									<form action=index.php?id=zmien_artykul_podmenu method=POST>
									<textarea class=ckeditor id=editor9  style=width:90; height: 100%; name=tresc lang=pl>$p[artykul]</textarea>
									<input type=hidden name=ktory value=$_GET[jaki] />
									<input type=submit name=zmien_artykul_podmenu value=Zmień />
									<input type=reset value=Resetuj />
									</div>"; }
									   }

			break;
case 'usun_pozycje':
	  $zapytanie = "DELETE FROM  podmenu WHERE id_menu=$_GET[co]";
	  $wstaw = mysql_query($zapytanie,$polaczenie);
	  	
		if ($wstaw)
			echo "<p>Usunięto pozycję podmenu</p>";
		else
			echo "<p>Usuwanie podmenu nie powiodło się</p>";
	  $zapytanie = "DELETE FROM  menu WHERE id=$_GET[co]";
	  $wstaw = mysql_query($zapytanie,$polaczenie);
	  	
		if ($wstaw)
			echo "<p>Usunięto pozycję menu</p>";
		else
			echo "<p>Usuwanie menu nie powiodło się</p>";
      break;
case 'dodaj_podmenu':
if ($_POST['dodajpodmenu']) {

	  $nazwa =trim($_POST['nazwa']);
	  $url = str_ireplace(' ','_',trim($_POST['opis']));
	  $zapytanie = "INSERT INTO podmenu (id_menu,nazwa,artykul,url) VALUES ('$_POST[ktory]','$nazwa','<p>Tutaj należy wpisać artykuł</p>','$url')";
	  $wstaw = mysql_query($zapytanie,$polaczenie);
	  	
		if ($wstaw)
			echo "<p>Dodano nową pozycję podmenu</p>";
		else
			echo "<p>Dodanie nowej pozycji podmenu nie powiodło się</p>";
        if ($_POST['podmenu']) {
			$query = "UPDATE menu SET podmenu = 'T' WHERE id = '$_POST[ktory]'";
			$wstaw = mysql_query($query,$polaczenie);
			if ($wstaw)
			echo "<p>Aktualizacja menu powiodła się.</p>";
		else
			echo "<p>Aktualizacja menu nie powiodła się!</p>";
						    }
}			
      break;

}
			$uslugi = "SELECT * FROM menu";
            $uslugi_res = mysql_query($uslugi,$polaczenie) or die ('Błąd: ' . mysql_error());
								if(!$uslugi_res) echo "Błąd bazy danych"; else
		    			      while($u = mysql_fetch_assoc($uslugi_res))		 
		    			        if($u["url"]==$_GET["id"])
						      {
								
								if($u["podmenu"]=='N')
								 {
								  	
								  echo "<div id=blok>";
								  echo "<strong>Nazwa pozycji w menu:</strong> $u[pozycja]</br>";
						          echo "<strong>Krótki opis:</strong> $u[url]";
								  echo "<center><h1>Artykuł</h1></center>";				  
							      echo $u["artykul"];
								  echo "<dd><a href=index.php?id=zmien_artykul_menu&co=$u[id]>zmień artykuł</a></dd>";
								  echo "<dd><a href=index.php?id=zmien_nazwe_menu&co=$u[id]>zmień nazwę pozycji menu</a></dd>";
								  echo "<dd><a href=index.php?id=zmien_opis_menu&co=$u[id]>zmień opis</a></dd>";
								  echo "<dd><a href=index.php?id=usun_pozycje&co=$u[id]>usuń pozycję</a></dd>";
								  echo "</div>";
						        echo "<div id=blok>
									<form action=index.php?id=dodaj_podmenu method=POST>
									<p>Dodaj podmenu:</p><br /> 
									<input type=text size=40 name=nazwa required /><br />
									<p>Krótki opis:</p><br />
									<input type=hidden name=podmenu value=T />
									<input type=hidden name=ktory value=$u[id] />
									<input type=text size=40 name=opis required /><br /> 
									<input type=submit name=dodajpodmenu value=Dodaj />
									<input type=reset value=Resetuj />
									</div>";
								 }else
							    {
								  echo "<div id=blok>";
								  echo "<strong>Nazwa pozycji w menu:</strong> $u[pozycja]</br>";
						          echo "<strong>Krótki opis:</strong> $u[url]";
								  echo "<dd><a href=index.php?id=zmien_nazwe_menu&co=$u[id]>zmień nazwę pozycji menu</a></dd>";
								  echo "<dd><a href=index.php?id=zmien_opis_menu&co=$u[id]>zmień opis</a></dd>";
								  echo "<dd><a href=index.php?id=usun_pozycje&co=$u[id]>usuń pozycję</a></dd>";
								  echo "</div>";									
								 	$pmenu = "SELECT * FROM podmenu WHERE id_menu=$u[id]";
                                    $pmenu_res = mysql_query($pmenu,$polaczenie) or die ('Błąd: ' . mysql_error());
								    if(!$pmenu_res) echo "Błąd bazy danych"; else
		    			            while($p = mysql_fetch_assoc($pmenu_res))
									{
								  echo "<div id=blok>";
								  echo "<strong>Nazwa pozycji w menu:</strong> $p[nazwa]</br>";
						          echo "<strong>Krótki opis:</strong> $p[url]";
								  echo "<center><h1>Artykuł</h1></center>";				  
							      echo $p["artykul"];
								  echo "<dd><a href=index.php?id=zmien_artykul_podmenu&jaki=$p[id]>zmień artykuł</a></dd>";
								  echo "<dd><a href=index.php?id=zmien_nazwe_podmenu&jaki=$p[id]>zmień nazwę pozycji menu</a></dd>";
								  echo "<dd><a href=index.php?id=zmien_opis_podmenu&jaki=$p[id]>zmień opis</a></dd>";
								  echo "<dd><a href=index.php?id=usun_podmenu&num=$p[id]>usuń artykuł</a></dd>";
								  echo "</div>";
									}
						        echo "<div id=blok>
									<form action=index.php?id=dodaj_podmenu method=POST>
									<p>Dodaj podmenu:</p><br /> 
									<input type=text size=40 name=nazwa required /><br />
									<p>Krótki opis:</p><br />
									<input type=hidden name=ktory value=$u[id] />
									<input type=text size=40 name=opis required /><br /> 
									<input type=submit name=dodajpodmenu value=Dodaj />
									<input type=reset value=Resetuj />
									</div>";
								}
								break;
		    			      }


			
?>
</section>
 
<!-- footer -->
<footer>
<p></p>
</footer>
 
</div>
 
</body>
</html>
test ... :)