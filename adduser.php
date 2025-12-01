<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</HEAD>
<BODY>
<?php
$user=$_POST['user']; // login z formularza
$user = htmlentities ($user, ENT_QUOTES, "UTF-8");
$pass=$_POST['pass']; // hasło z formularza
$pass = htmlentities ($pass, ENT_QUOTES, "UTF-8"); 
$repeatpass=$_POST['repeatpass'];
$repeatpass = htmlentities($repeatpass, ENT_QUOTES, "UTF-8");
$link = mysqli_connect("localhost", "root", "", "01000928_z7"); // połączenie z BD – wpisać swoje dane
if(!$link) { echo"Błąd: ". mysqli_connect_errno()." ".mysqli_connect_error(); } // obsługa błędu połączenia z BD
mysqli_query($link, "SET NAMES 'utf8'"); // ustawienie polskich znaków
$result = mysqli_query($link, "SELECT * FROM users WHERE username='$user'"); // wiersza, w którym login=login z formularza
$rekord = mysqli_fetch_array($result); // wiersza z BD, struktura zmiennej jak w BD
if(!$rekord) //Jeśli brak, to nie ma użytkownika o podanym loginie
{if($pass == $repeatpass)
    {
    $sql = mysqli_query($link,"INSERT INTO users (username,password) VALUES ('$user','$pass')");
    mysqli_close($link); // zamknięcie połączenia z BD  
header("Location: http://index.kacwac000.online/z7/login.php");
    }
    else{
        mysqli_close($link);
        echo "Hasla sie nie zgadzaja";
    }
}
else
{ 
    mysqli_close($link);
    echo "Uzytkownik istnieje";
}
?>
</BODY>
</HTML>