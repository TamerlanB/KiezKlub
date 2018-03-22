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


<title>Kiez Klub Hessenwinkel</title>

</head><body>

	<center>
 	<table>
 	<tr><center> 	
 	<td><img src="bild/kkhw.png" height="155" alt="Logo" align="left" /></td>
	</tr>
	</table>





</body></html>
