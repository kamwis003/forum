<?php
session_start();
$tname=$_POST['tname'];
$tname = htmlentities ($tname, ENT_QUOTES, "UTF-8");
$message=$_POST['message']; // login z formularza
$message = htmlentities ($message, ENT_QUOTES, "UTF-8");
$target_dir = "./files/";
$target_file = $target_dir . "/". basename($_FILES["fileToUpload"]["name"]);
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
if($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "gif" )
{
$type = "img";
}
else if($imageFileType == "mp3")
{
    $type = "audio";
}
else if($imageFileType == "mp4")
{
    $type = "video";
}
else
{
$type = "other";
}
if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file))
{ echo htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " uploaded."; }
else { echo "Error uploading file."; }
$connection = mysqli_connect("localhost", "root", "", "01000928_z7");
if (!$connection)
{
echo " MySQL Connection error." . PHP_EOL;
echo "Errno: " . mysqli_connect_errno() . PHP_EOL;
echo "Error: " . mysqli_connect_error() . PHP_EOL;
exit;
}
else{
    $target_file2 = basename($_FILES["fileToUpload"]["name"]);
    $user = $_SESSION ['user_login'];
$result = mysqli_query($connection, "SELECT * FROM users WHERE username='$user'");
$row = mysqli_fetch_array($result);
$id = $row[0];
$result = mysqli_query($connection, "INSERT INTO threads (tname,id) VALUES ('$tname','$id');") or die ("DB error: $dbname");
$result2 = mysqli_query($connection, "SELECT * FROM threads WHERE tname='$tname'");
$row2 = mysqli_fetch_array($result2);
$tid = $row2[0];
$result3 = mysqli_query($connection, "INSERT INTO messages (tid,id,message,file, ext) VALUES ('$tid', '$id', '$message', '$target_file2', '$type');") or die ("DB error: $dbname");
mysqli_close($connection);
}
header ('Location: viewforum.php');
?>