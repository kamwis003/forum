<?php declare(strict_types=1); // włączenie typowania zmiennych w PHP >=7
$link = mysqli_connect("localhost", "root", "", "01000928_z7");
$ipaddress = $_SERVER["REMOTE_ADDR"];
function ip_details($ip) {
$json = file_get_contents ("http://ipinfo.io/{$ip}/geo");
$details = json_decode ($json);
return $details;
}
$details = ip_details($ipaddress);
$ipgoscia =  $details -> ip;
$sql = mysqli_query($link,"INSERT INTO goscieportalu (ipaddress) VALUES ('$ipgoscia')");
$tabela =  mysqli_query($link,"SELECT * FROM goscieportalu");
$linki = "https://www.google.pl/maps/place/";
$linki .= $ipgoscia;
$row = mysqli_fetch_assoc($tabela);
?>
<table border ="1" cellspacing="0" cellpadding="10">
    <tr>
    <th>ID</th>
    <th>Adres IP</th>
    <th>Czas</th>
    <th>Lokalizacja</th>
</tr>
<?php
while($row = mysqli_fetch_assoc($tabela)){
    ?>
    <tr>
   <td><?php echo $row['id']; ?> </td>
   <td><?php echo $row['ipaddress']; ?> </td>
   <td><?php echo $row['datetime']; ?> </td>
   <td><a  href="<?= $linki ?>" >Link</a></td>
 <tr>
 <?php
}
mysqli_close($link);
?>
<a href = "viewforum.php">Do portalu</a>