html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</HEAD>
<BODY>
<?php
$user=$_POST['user']; // login z formularza
$user = htmlentities ($user, ENT_QUOTES, "UTF-8");
$pass=$_POST['pass']; // hasło z formularza
$pass = htmlentities ($pass, ENT_QUOTES, "UTF-8"); 
$link = mysqli_connect("localhost", "root", "", "01000928_z7"); // połączenie z BD – wpisać swoje dane
if(!$link) { echo"Błąd: ". mysqli_connect_errno()." ".mysqli_connect_error(); } // obsługa błędu połączenia z BD
mysqli_query($link, "SET NAMES 'utf8'"); // ustawienie polskich znaków
$result = mysqli_query($link, "SELECT * FROM users WHERE username='$user'"); // wiersza, w którym login=login z formularza
$rekord = mysqli_fetch_array($result); // wiersza z BD, struktura zmiennej jak w BD
if(!$rekord) //Jeśli brak, to nie ma użytkownika o podanym loginie
{
mysqli_close($link); // zamknięcie połączenia z BD
echo "Brak użytkownika o takim loginie !"; // UWAGA nie wyświetlamy takich podpowiedzi dla hakerów
header("Location: http://index.kacwac000.online/z7/login.php");
}
else
{ // jeśli $rekord istnieje
if($rekord['password']==$pass) // czy hasło zgadza się z BD
{
echo "Logowanie Ok. User: {$rekord['username']}. Hasło: {$rekord['password']}";
session_start();
$_SESSION ['loggedin'] = true;
$_SESSION ['user_login'] = $user;
$_SESSION['user_id'] = $rekord['id'];
header("Location: http://index.kacwac000.online/z7/geo.php");
}
else
{
mysqli_close($link);
echo "Błąd w haśle !"; // UWAGA nie wyświetlamy takich podpowiedzi dla hakerów
header("Location: http://index.kacwac000.online/z7/login.php");
}
}
?>
</BODY>
</HTML>