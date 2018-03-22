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

<title>Kiezklub Rahnsdorf e.V.</title>

</head>
<body>
<?php

if($_POST[dbpflege]=="1" AND $_POST[dbpflegebest]=="1")
	{require_once("cleaning.php");
	};


if(isset($_POST[bestaetigt]))
	{mysql_query("UPDATE gbaustein set autorisiert='$datum', name='$_POST[name]'  WHERE idgbaustein='$_POST[bestaetigt]'");
	};
	
if(isset($_POST[abuse]))
	{mysql_query("UPDATE gbaustein set autorisiert='$datum', abuse='1'  WHERE idgbaustein='$_POST[abuse]'");
	};

// hauptmenueeintrag
if(isset($_POST[hmmenu]) AND isset($_POST[hmstellung]))
	{$hmergebnis=mysql_query("SELECT * FROM menu WHERE menu='$_POST[hmmenu]'");
	$hmzahl=mysql_num_rows($hmergebnis);
	if($hmzahl=="0")
		{$zergebnis=mysql_query("SELECT * FROM menu ORDER by hmstellung, stellung");
		$zzahl=mysql_num_rows($zergebnis);
		$z="0";
		if($_POST[hmstellung]<"1"){$nhmz="1";}else{$nhmz=$_POST[hmstellung];};
		$eintragnr="1";
		$eingetragen="0";
		$merknr="1";
		WHILE($z<$zzahl)
			{$hmdaten=mysql_fetch_assoc($zergebnis);
			if($hmdaten[hmstellung]>=$_POST[hmstellung] AND $eingetragen=="0")
				{mysql_query("INSERT INTO menu (menu, hmstellung, stellung, ueberschrift) 
				VALUES ('$_POST[hmmenu]','$_POST[hmstellung]', '0','$_POST[hmueberschrift]')");
				$eintragnr++;$fehler="<br>Men&uuml; wurde zugef&uuml;gt !<br>"; $eingetragen="1";
				};
			if($hmdaten[hmstellung]>$merknr){$eintragnr++;};
			mysql_query("UPDATE menu set hmstellung='$eintragnr' WHERE idmenu='$hmdaten[idmenu]'");
			$merknr=$hmdaten[hmstellung];			
			$z++;};
		if($eingetragen=="0")
			{mysql_query("INSERT INTO menu (menu, hmstellung, stellung, ueberschrift) 
				VALUES ('$_POST[hmmenu]','$_POST[hmstellung]', '0','$_POST[hmueberschrift]')");
			};
		};
	};

// Untermenueeintrag
if(isset($_POST[menu]) AND isset($_POST[umenu]))
	{$umergebnis=mysql_query("SELECT * FROM menu WHERE hmstellung='$_POST[menu]' ORDER BY stellung");
	$umzahl=($umergebnis);
	$z="0";
	$eintragnr="1";
	$eingetragen="0";
	$merknr="1";
	WHILE($z<$umzahl)
		{$umdaten=mysql_fetch_assoc($umergebnis);
		if($umdaten[stellung]>=$_POST[stellung] AND $eingetragen=="0")
				{mysql_query("INSERT INTO menu (untermenu, hmstellung, stellung, ueberschrift) 
				VALUES ('$_POST[umenu]','$_POST[menu]', '$_POST[stellung]','$_POST[ueberschrift]')");
				$eintragnr++;$fehler="<br>Men&uuml; wurde zugef&uuml;gt !<br>"; $eingetragen="1";
				};
		if($umdaten[stellung]>$merknr){$eintragnr++;};
		mysql_query("UPDATE menu set stellung='$eintragnr' WHERE idmenu='$umdaten[idmenu]' AND menu=''");
		$merknr=$umdaten[stellung];		
		$z++;};
	if($eingetragen=="0")	
			{mysql_query("INSERT INTO menu (untermenu, hmstellung, stellung, ueberschrift) 
				VALUES ('$_POST[umenu]','$_POST[menu]', '$_POST[stellung]','$_POST[ueberschrift]')");
			};
	};
		

if($_POST[logout]=="1")
	{$_SESSION[admin]="0";
	unset($_SESSION[s]);}; 

if(isset($_POST[npasswort]) AND isset($_POST[npasswortwh]))
	{if($_POST[npasswort]==$_POST[npasswortwh] AND strlen($_POST[npasswort])>"10")
		{mysql_query("UPDATE user set passwort='$_POST[npasswort]' WHERE iduser='1'");
		$fehler="<br>Passwort wurde ge&auml;ndert!<br>Das Neue Passwort wird beim n&auml;chsten Login aktiv !<br>";
		}
		else{
		$fehler="<br>Passwort wurde nicht ge&auml;ndert!<br>Die neuen Passworte stimmten nicht &uuml;berein oder sind unter 10 Zeichen lang!<br>";		
		};
	}; 

if($_GET[menunr]!="")
	{$menunr=$_GET[menunr];
	$mabfrage="SELECT * from menu where idmenu='$menunr'";
	$menuergebnis=mysql_query($mabfrage) or die ("Menuabfrage erfolglos");
	$menudaten=mysql_fetch_assoc($menuergebnis);
	$_SESSION[menunr]=$menudaten[idmenu];
	$_SESSION[seite]=round($menudaten[seite]);
	$_SESSION[menustellung]=round($menudaten[stellung]);
	};
?>

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
<center><table width="750"><tr><td align="right">
		<center><font size="5">System-Administration: </center></font>
<?php
echo "<center><font color='red'>", $fehler, "<p></p></font>";
echo "<center><font color='red'>", $_SESSION[fehler], "<p></p></font>";
unset($fehler);
unset($_SESSION[fehler]);

if($_SESSION[admin]!="1")
	{$abfrage="SELECT * FROM user WHERE iduser='1'";
	$ergebnis=mysql_query($abfrage);
	$daten=mysql_fetch_assoc($ergebnis);
	$dbpasswort=$daten[passwort];
	

	if($_POST[passwort]==$dbpasswort)
		{$_SESSION[admin]="1";
		$_SESSION[website]=$daten[website];
		mysql_query("UPDATE user set lastlogin='$datum' WHERE iduser='1'");
		}
		else
		{$_SESSION[s]=$_SESSION[s]+1; ?>

		<center><form style="margin:0;" name="passabfrage" action="<?PHP $PHP_SELF = $_SERVER['PHP_SELF']; echo $PHP_SELF; ?>" method="post" target="_parent">
		Passwort: <input type="password" name="passwort">
		<input type="submit" id="sbutton" class="sd" name="senden" value="Anmelden" />
		</form>

		<?php
		if($_SESSION[s]>="5"){header("Location: index.php");};
		};
	};
	
	
	
if($_SESSION[admin]=="1")	
	{$_SESSION[s]="1";
	$gbabfrage="SELECT * FROM gbaustein WHERE autorisiert IS NULL OR autorisiert='0000-00-00 00:00:00'";
	$gbergebnis=mysql_query($gbabfrage);
	$gbzahl=mysql_num_rows($gbergebnis);
	if($gbzahl>"0")
		{
		?>
		Neue G&auml;stebucheintr&auml;ge:
		<p></p><center>
		<table border="1"><tr><td>Button</td><td>Abuse</td><td><center>Inhalt</td></tr><tr><td>
		<?php
		$zahl="0";
		while ($zahl<$gbzahl)
			{$gbdaten=mysql_fetch_assoc($gbergebnis);
			?>
			<form style="margin:0;" name="bestaetigt" action="<?PHP $PHP_SELF = $_SERVER['PHP_SELF']; echo $PHP_SELF; ?>" method="post" target="_parent">
			<input type="hidden" name="bestaetigt" value="<?php echo $gbdaten[idgbaustein];?>" />
			<center>Name:<br><input type="text" name="name" size="7" maxlength="60" value="<?php echo $gbdaten[name]; ?>">
			<br><input type="submit" id="sbutton" class="sd" name="senden" value="best&auml;tigen" />
			</form>
			<br>
			<form style="margin:0;" name="loeschen" action="<?PHP $PHP_SELF = $_SERVER['PHP_SELF']; echo $PHP_SELF; ?>" method="post" target="_parent">
			<input type="hidden" name="abuse" value="<?php echo $gbdaten[idgbaustein];?>" />
			<input type="submit" id="sbutton" class="sd" name="senden" value="l&ouml;schen" />
			</form>
			</td><td><center><?php echo $gbdaten[abuse];?>
			</td><td><?php echo $gbdaten[text];?>
			</td></tr><tr><td>
			<?php
			$zahl++;};
			?>
			</td></tr></table>
			<p></p>
			<hr>
			<p></p>
		<?php }; ?>	
	<center><font size='5'>W&auml;hlen Sie links das Men&uuml;, welches Sie &auml;ndern wollen !<br>Oder:</font>
	<hr>
	<font size="3">

<!Vergeben Sie ein neues Passwort:>
	<form style="margin:0;" name="passwechsel" action="<?PHP $PHP_SELF = $_SERVER['PHP_SELF']; echo $PHP_SELF; ?>" method="post" target="_parent">
	neues Passwort: <input type="password" name="npasswort" size="15">
	Passwortkontrolle: <input type="password" name="npasswortwh" size="15">
	<input type="submit" id="sbutton" class="sd" name="senden" value="Passwort &auml;ndern" />
	</form>

	<hr>

<!	Legen Sie ein neues Hauptmen&uuml; an:>

	<p></p>
	<form style="margin:0;" name="menuanlage" action="<?PHP $PHP_SELF = $_SERVER['PHP_SELF']; echo $PHP_SELF; ?>" method="post" target="_parent">
	Name: <input type="text" name="hmmenu" size="8">
	Stellung: <input type="text" name="hmstellung" size="2">
	&Uuml;berschrift: <input type="text" name="hmueberschrift" size="20">
	<input type="submit" id="sbutton" class="sd" name="senden" value="Hauptmen&uuml; anlegen" />
	</form>	

<hr>
	<p></p>

<!	Legen Sie ein neues Untermen&uuml; an:>

	<p></p>
	<form style="margin:0;" name="menuanlage" action="<?PHP $PHP_SELF = $_SERVER['PHP_SELF']; echo $PHP_SELF; ?>" method="post" target="_parent">
	Hauptmen&uuml; w&auml;hlen: 
	<select name="menu" size="1" style=width:150px;  >
	<?php 
	$mabfrage="SELECT * FROM menu WHERE menu!='' ORDER BY menu, stellung";
	$mergebnis=mysql_query($mabfrage);
	$mzahl=mysql_num_rows($mergebnis);
	$zahl="0";
	while($zahl<$mzahl)
		{$mliste=mysql_fetch_assoc($mergebnis);
			?>
			<option selected value="<?php echo $mliste[hmstellung]; ?>"><?php echo $mliste[menu]; ?>  </option>
			<?php 
		$zahl++; 
		}; ?>
	</select>

	Untermen&uuml;: <input type="text" name="umenu" size="15">
	<br>Stellung: <input type="text" name="stellung" size="2">
	&Uuml;berschrift: <input type="text" name="ueberschrift" size="20">
	<input type="submit" id="sbutton" name="senden" value="Untermen&uuml; anlegen" />
	</form>	


	<hr>	

	<form style="margin:0;" name="logout" action="<?PHP $PHP_SELF = $_SERVER['PHP_SELF']; echo $PHP_SELF; ?>" method="post" target="_parent">
	<input type="hidden" name="dbpflege" value="1" />
	<input type="submit" id="sbutton" style=width:250px;  name="senden" value="Datenbankbereinigung" />
	<input type="checkbox" name="dbpflegebest" value="1">
	</form>
	
	<hr>	

	<form style="margin:0;" name="logout" action="<?PHP $PHP_SELF = $_SERVER['PHP_SELF']; echo $PHP_SELF; ?>" method="post" target="_parent">
	<input type="hidden" name="logout" value="1" />
	<input type="submit" id="sbutton" style=width:250px;  name="senden" value="Logout" />
	</form>
	
	<?php
	};


	?>
</td></tr></table>
</div>


<?php
unset($fehler);
mysql_close();
?>
</body></html>
