<?php
session_start();
ob_start();
include("dbconnect.php");
$ip = getenv ( 'REMOTE_ADDR' );
$datum = date("Y-m-d H:i");


if(isset($_SESSION[UserID]))
	{$abfrage="SELECT * from user where iduser=$_SESSION[UserID]";
	$ergebnis=mysql_query($abfrage) or die ("Datenbankfehler");
	$userdat=mysql_fetch_assoc($ergebnis);
	};
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html><head>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
<link href="stylesheet.css" rel="stylesheet" type="text/css">
<meta name="keywords" content="Hessenwinkel, Nachbarschaftshilfe,  Betreuung,   Rahnsdorf,  Wilhelmshagen,  Nachbarschaftszentrum, NBZ, Kietzklub, Kitzklub, Kiezclub, Kitzclub, Kietzclub, Förderverein, Jugend, Jugendhilfe, altenhilfe, altenbetreuung, senioren, seniorenklub, Bürgerbetreuung, buergerbetreuung, Veranstaltungen, kulturveranstaltungen, Mehrgenerationen">

<title>Kiezklub Rahnsdorf e.V.</title>

</head><body>

<div id="kopf">
<?php
require_once("kopf.php");
?>
</div>

<div id="links">
<?php
require_once("menu.php");
?>
</div>



<div id="inhalt">

<center>
<table cellspacing="10" cellpadding="20" width="850"><tr><td>
<font size="5"><b>Förderung der Jugend- und Altenhilfe<br>Unterstützung der sozialen Arbeit<br>Bürgerbetreuung<br>Beratung</font></b>
</td><td>
<img src="bild/aussenansicht.JPG" width="250" border="0" alt="Aussenansicht" align="bottom">
</td></tr></table>
<center><font size="5"><center><i>KIEZKLUB Rahnsdorf</i>
<center><font size="4"><center>F&uuml;rstenwalder Alle 362 / Ecke Lutherstra&szlig;e<br>12589 Berlin-Hessenwinkel<br>Tel: 030 648 60 90

<table width="850"><tr><td>
<img src="bild/Basteln310.JPG" width="250" border="0" alt="Basteln" align="bottom">
</td><td>
<img src="bild/Gaeste336.JPG" width="250" border="0" alt="Gaeste" align="bottom">
</td><td>
<img src="bild/Zauberer367.JPG" width="250" border="0" alt="Zauberer" align="bottom">
</td></tr></table>


<font size="1"><p>Die Seite wurde erstellt als "Open-Source" unter GNU-Lizenz.</font>
</div>



<?php
mysql_close();
?>

</body></html>
