<?php
session_start();
ob_start();
include("dbconnect.php");
$ip = getenv ( 'REMOTE_ADDR' );
$datum = date("Y-m-d H:i");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html><head>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
<link href="stylesheet.css" rel="stylesheet" type="text/css">

<?php
// TB auslesen
$tbergebnis=mysql_query("SELECT * FROM tbaustein JOIN (reihenfolge,baustein,menu) ON menu.menuid=baustein.menu_idmenu AND baustein.idbaustein=reihenfolge.baustein_idbaustein
AND reihenfolge.idreihenfolge=tbaustein.reihenfolge.idreihenfolge");
$tbzahl=mysql_num_rows($tbergebnis);

echo "<br>TB: ",$tbzahl;

?>
</body></html>
