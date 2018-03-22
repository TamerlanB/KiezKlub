
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html><head>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
<link href="stylesheet.css" rel="stylesheet" type="text/css">

<title>Onlinesozialmarkt & Platon</title>

</head><body>
	<ul id="Navigation">
	<li><a href="index.php" tabindex="900" >Home</a></li>


<?php
// Setsession seite
$_SESSION[menu]=$_GET[menunr];

// auslesen der Tabelle menu 
$abfrage="SELECT * from menu order by hmstellung, stellung";
$ergebnis=mysql_query($abfrage) or die ("Datenbankproblem");
$anzahl=mysql_num_rows($ergebnis);
$z=0;
while($anzahl>$z)
	{
	$zeile=mysql_fetch_assoc($ergebnis);
	if($zeile[menu]!="")
		{if($zeile[idmenu]==$_SESSION[menu] OR $zeile[hmstellung]==$_GET[hmnr])
			{ ?>
			<li class="gw"><a href="art0.php?menunr=<?php echo $zeile[idmenu]; ?>&hmnr=<?php echo $zeile[hmstellung]; ?> " >
			<?php echo substr($zeile[menu],0,15); ?></a></li>
			<?php
			}
			else
			{?>
			<li><a href="art0.php?menunr=<?php echo $zeile[idmenu]; ?>&hmnr=<?php echo $zeile[hmstellung]; ?> " >
			<?php echo substr($zeile[menu],0,15); ?></a></li>
			<?php
			};
		};
	if($zeile[untermenu]!="" AND $zeile[hmstellung]==$_GET[hmnr])
		{if($zeile[idmenu]==$_SESSION[menu])
			{ ?>
			<li class="umgw"><a href="art0.php?menunr=<?php echo $zeile[idmenu]; ?>&hmnr=<?php echo $zeile[hmstellung]; ?> " >
			<?php echo substr($zeile[untermenu],0,15); ?></a></li>
			<?php
			}
			else
			{?>
			<li class="um"><a href="art0.php?menunr=<?php echo $zeile[idmenu]; ?>&hmnr=<?php echo $zeile[hmstellung]; ?> " >
			<?php echo substr($zeile[untermenu],0,15); ?></a></li>
			<?php
			};
		};		
		
	$z++;};

?>
	<li><a href="art8.php" tabindex="900" >G&auml;steChat</a></li>
	<li><a href="art9.php" tabindex="900" >ADMIN</a></li>

</ul>
	<p></p>
<?php
if($_SESSION[admin]=="1")
{
	if(isset($_SESSION[menuanz]))
		{?>
		<form style="margin:0;" action="<?PHP $PHP_SELF = $_SERVER['PHP_SELF']; echo $PHP_SELF; ?>" method="post" target="_parent">
		<input type="hidden" name="menuanz" value="0" />
		<input type="hidden" name="menunr" value="<?php echo $menunr; ?>">
		<input type="submit" id="sbutton" class="sdh" style=width:110px; name="senden" value="Men&uuml;anzeige" />
		</form>
		<?php
		};	
	if(isset($_SESSION[bauswahl]))
		{?>
		<form style="margin:0;" action="<?PHP $PHP_SELF = $_SERVER['PHP_SELF']; echo $PHP_SELF; ?>" method="post" target="_parent">
		<input type="hidden" name="bauswahl" value="n" />
		<input type="hidden" name="menunr" value="<?php echo $menunr; ?>">
		<input type="submit" id="sbutton" class="sdh" style=width:110px; name="senden" value="Neuwahl" />
		</form>
		<?php
		};	
echo "<p></p><center><table border='1'><tr><td>";
	if(isset($liste)){foreach($liste as $zs){echo "<font size='2'>",$zs, "</tr></td><tr><td>";};};
echo "</td></tr></table>";
};
	?>	


</body></html>
