<?php
declare(strict_types=1); // włączenie typowania zmiennych w PHP >=7
session_start(); // zapewnia dostęp do zmienny sesyjnych w danym pliku
$tid = $_GET['tid'];
?>
<a href="viewforum.php">Powrót</a><br>
Temat:
<?php
$connection = mysqli_connect("localhost", "root", "", "01000928_z7");
$result1 = mysqli_query($connection, "SELECT * from threads WHERE tid='$tid'");
$threadresults = mysqli_fetch_array($result1);
$threadname = $threadresults[1];
echo $threadname;
?>
<br>
Dodaj posta<br>
<form action="addpost.php" method="post" enctype="multipart/form-data">
Wiadomość:<input type="text" name="message"><br>
Plik:<input type="file" name="fileToUpload" id="fileToUpload"><br>
<input type='hidden' name='tid' value='<?php echo "$tid";?>'/> 
<input type="submit" value="Wyślij" name="submit" <?php if (!isset($_SESSION['loggedin']))
{echo "disabled";}?>>
</form>
<?php
$connection = mysqli_connect("localhost", "root", "", "01000928_z7");
if (!$connection)
{
echo " MySQL Connection error." . PHP_EOL;
echo "Errno: " . mysqli_connect_errno() . PHP_EOL;
echo "Error: " . mysqli_connect_error() . PHP_EOL;
exit;
}
$tid = $_GET['tid'];
$result = mysqli_query($connection, "SELECT * from messages WHERE tid='$tid'");
print "<TABLE CELLPADDING=5 BORDER=1>";
print "<TR><TD>messageid</TD><TD>username</TD><TD>wiadomość</TD><TD>Plik</TD><TD>czas</TD></TR>\n";
while ($row = mysqli_fetch_array ($result))
{
$messageid = $row[0];
$userid = $row[2];
$result2 = mysqli_query($connection, "SELECT * from users WHERE id='$userid'");
$result2fetch = mysqli_fetch_array($result2);
$username = $result2fetch[1]; 
$message = $row[3];
$message = preg_replace("/\bcholera\b/i", "co przeklinasz?", $message);
$filename= $row[4];
$datetime= $row[5];
print "<TR><TD>$messageid</TD>";
print "<TD>$username</TD>";
print "<TD>$message</TD>";
if($row[6] == "img")
{
echo "<td>";?><img src="<?php echo "./files/" . $filename;?>" height="200" width="200"><?php echo "</td>";
}
else if($row[6] == "audio")
{
echo "<td>";?><audio controls><source src="<?php echo "./files/" . $filename;?>" type="audio/mp3"><?php echo "</td>";    
}
else if($row[6] == "video")
{
echo "<td>";?><video width="320" height="240" controls><source src="<?php echo "./files/" . $filename;?>" type="video/mp4"><?php echo "</td>";
}
else
{
    echo "<td></td>";
}
print "<TD>$datetime</TD>";
//if($_SESSION['user_login'] == $username)
//{
//    print "<TD>test</TD>";
//}
//else 
//{
//    print "<TD></TD>";
//}
print "</TR>\n";
}
print "</TABLE>";
mysqli_close($connection);
?>