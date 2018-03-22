<?php
session_start();
ob_start();
include("dbconnect.php");
$ip = getenv ( 'REMOTE_ADDR' );
$datum = date("Y-m-d H:i");

if(isset($_POST[neueintrag]))
	{mysql_query("INSERT INTO gbaustein (name,text,anlagedatum,ip) VALUES ('$_POST[name]','$_POST[neueintrag]','$datum','$ip')") or die ("db fehler");};

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html><head>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
<link href="stylesheet.css" rel="stylesheet" type="text/css">

<title>Onlinesozialmarkt & Platon</title>

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
<table width="750"><tr><td>
<center>
<table  cellspacing="5" cellpadding="5"><tr><td>
<font size="5"><center><u>G&auml;steChat : </u>
</td><td valign="top"><center>
<form style="margin:0;" name="menupflege" action="<?PHP $PHP_SELF = $_SERVER['PHP_SELF']; echo $PHP_SELF; ?>" method="post" target="_parent">
<input type="submit" id="sbutton" class="sd" style="width:200px; height:30px; font-size:16;  name="senden" value="Mitreden" />
<input type="hidden" name="neu" value="1">
</form>
</td><td><center>
<form style="margin:0;" name="menupflege" action="<?PHP $PHP_SELF = $_SERVER['PHP_SELF']; echo $PHP_SELF; ?>" method="post" target="_parent">
<input type="submit" id="sbutton" class="sd" style="width:200px; height:30px; font-size:16;  name="senden" value="Aktualisieren" />

</form>
</td></tr></table>


<p>
<?php 
if($_POST[neu]=="1")
	{ ?>
	<form style="margin:0;" name="menupflege" action="<?PHP $PHP_SELF = $_SERVER['PHP_SELF']; echo $PHP_SELF; ?>" method="post" target="_parent">
	<font size="3">Thema:<input type="text" name="name" size="12" maxlength="16"></font>
	<br>
	<textarea name="neueintrag" rows="2" cols="80"></textarea>
	<br>
	<input type="submit" id="sbutton"   class="sd" name="senden" value="Eintragen" />
	</form>
	<?php
	};

$abfrage="SELECT * FROM gbaustein WHERE abuse='0' ORDER BY anlagedatum";
$ergebnis=mysql_query($abfrage);
$anzahl=mysql_num_rows($ergebnis);
$z="0";
WHILE ($z<$anzahl)
	{$daten=mysql_fetch_assoc($ergebnis);
	if($daten[autorisiert]=='0000-00-00 00:00:00')
		{$uadat=$uadat."\n".substr($daten[anlagedatum],0,10)." - ".$daten[name]."\n".$daten[text]."\n";
		}
		else
		{$adat=$adat."\n".substr($daten[anlagedatum],0,10)." - ".$daten[name]."\n".$daten[text]."\n";
		};
	$z++;};
if(isset($uadat))
			{
?>
<font size="1" color="red"><br>Die rot gekennzeichneten Eintr&auml;ge sind neu, noch nicht autorisiert und daher von uns nicht verantwortet.</font>
<br><textarea name="text" rows="12" cols="80" ><?php echo $uadat; ?></textarea> </font><br>
<?php		}; ?>
<font size="3">Beitr&auml;ge:<br><textarea name="text" rows="22" cols="80" ><?php echo $adat; ?></textarea> 
</font>
</td></tr></table>
</div>


<?php
mysql_close();
?>
</body></html>
