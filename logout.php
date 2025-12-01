<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Wącior</title>
</head>

<body class="secondary-template">

    

    <main>
        <?php
        session_start();
       session_unset();
       header("HTTP/1.1 303 See Other");
header("Location: http://index.kacwac000.online/z7/login.php");

?>
    
    </main>
</body>

</html>