<?php declare(strict_types=1);  /* Ta linia musi byc pierwsza */ 
session_start();
$login = $_SESSION['user_login'];
?>
<a href = "logout.php">Wyloguj</a><br>
Zalogowany jako:
<?php
if (!isset($_SESSION['loggedin']))
{
    echo "Gość";
}
else
{
echo $login;
}
?>
<br><br>
Utwórz temat<br>
<form action="newtopic.php" method="post" enctype="multipart/form-data">
Temat:<input type="text" name="tname" maxlength="20" size="20"><br>
Wiadomość:<input type="text" name="message"><br>
Plik:<input type="file" name="fileToUpload" id="fileToUpload"><br>
<input type="submit" value="Utwórz" name="submit" <?php if (!isset($_SESSION['loggedin']))
{echo "disabled";}?>>
</form>
<?php
$connection = mysqli_connect("localhost", "root", "", "01000928_z7");
$result = mysqli_query($connection, "Select * from threads Order by tid Desc") or die ("DB error: $dbname");
while ($row = mysqli_fetch_array ($result))
{
 echo "<a href=viewtopic.php?tid=$row[0]>$row[1]</a><br>";
}