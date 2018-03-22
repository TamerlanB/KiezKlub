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
$ergebnis=mysql_query("SELECT * FROM tbaustein JOIN (reihenfolge,baustein,menu) ON menu.idmenu=baustein.menu_idmenu AND baustein.idbaustein=reihenfolge.baustein_idbaustein
AND reihenfolge.idreihenfolge=tbaustein.reihenfolge_idreihenfolge");
$zahl=mysql_num_rows($ergebnis);
$z="0";
WHILE($z<$zahl)
	{$daten=mysql_fetch_assoc($ergebnis);
	$haben_idmenu[]=$daten[idmenu];
	$haben_idbaustein[]=$daten[idbaustein];
	$haben_idreihenfolge[]=$daten[idreihenfolge];
	$haben_idtbaustein[]=$daten[idtbaustein];
	$z++;};

$ergebnis=mysql_query("SELECT * FROM bbaustein JOIN (reihenfolge,baustein,menu) ON menu.idmenu=baustein.menu_idmenu AND baustein.idbaustein=reihenfolge.baustein_idbaustein
AND reihenfolge.idreihenfolge=bbaustein.reihenfolge_idreihenfolge");
$zahl=mysql_num_rows($ergebnis);
$z="0";
WHILE($z<$zahl)
	{$daten=mysql_fetch_assoc($ergebnis);
	$haben_idmenu[]=$daten[idmenu];
	$haben_idbaustein[]=$daten[idbaustein];
	$haben_idreihenfolge[]=$daten[idreihenfolge];
	$haben_idbbaustein[]=$daten[idbbaustein];
	$haben_bildname[]=$daten[bild];
	$z++;};

$ergebnis=mysql_query("SELECT * FROM link JOIN (reihenfolge,baustein,menu) ON menu.idmenu=baustein.menu_idmenu AND baustein.idbaustein=reihenfolge.baustein_idbaustein
AND reihenfolge.idreihenfolge=link.reihenfolge_idreihenfolge");
$zahl=mysql_num_rows($ergebnis);
$z="0";
WHILE($z<$zahl)
	{$daten=mysql_fetch_assoc($ergebnis);
	$haben_idmenu[]=$daten[idmenu];
	$haben_idbaustein[]=$daten[idbaustein];
	$haben_idreihenfolge[]=$daten[idreihenfolge];
	$haben_idlink[]=$daten[idlink];
	$haben_linkname[]=$daten[link];
	$z++;};

$ergebnis=mysql_query("SELECT * FROM gbaustein JOIN (reihenfolge,baustein,menu) ON menu.idmenu=baustein.menu_idmenu AND baustein.idbaustein=reihenfolge.baustein_idbaustein
AND reihenfolge.idreihenfolge=gbaustein.reihenfolge_idreihenfolge");
$zahl=mysql_num_rows($ergebnis);
$z="0";
WHILE($z<$zahl)
	{$daten=mysql_fetch_assoc($ergebnis);
	$haben_idmenu[]=$daten[idmenu];
	$haben_idbaustein[]=$daten[idbaustein];
	$haben_idreihenfolge[]=$daten[idreihenfolge];
	$haben_idgbaustein[]=$daten[idgbaustein];
	$z++;};

// einzelne tabellen auslesen
$ergebnis=mysql_query("SELECT * FROM tbaustein");
$zahl=mysql_num_rows($ergebnis);
$z="0";
WHILE($z<$zahl)
	{$daten=mysql_fetch_assoc($ergebnis);
	$soll_idtbaustein[]=$daten[idtbaustein];
	$z++;};

$ergebnis=mysql_query("SELECT * FROM bbaustein");
$zahl=mysql_num_rows($ergebnis);
$z="0";
WHILE($z<$zahl)
	{$daten=mysql_fetch_assoc($ergebnis);
	$soll_idbbaustein[]=$daten[idbbaustein];
	$z++;};

$ergebnis=mysql_query("SELECT * FROM link");
$zahl=mysql_num_rows($ergebnis);
$z="0";
WHILE($z<$zahl)
	{$daten=mysql_fetch_assoc($ergebnis);
	$soll_idlink[]=$daten[idlink];
	$z++;};

$ergebnis=mysql_query("SELECT * FROM reihenfolge");
$zahl=mysql_num_rows($ergebnis);
$z="0";
WHILE($z<$zahl)
	{$daten=mysql_fetch_assoc($ergebnis);
	$soll_idreihenfolge[]=$daten[idreihenfolge];
	$z++;};

$ergebnis=mysql_query("SELECT * FROM baustein");
$zahl=mysql_num_rows($ergebnis);
$z="0";
WHILE($z<$zahl)
	{$daten=mysql_fetch_assoc($ergebnis);
	$soll_idbaustein[]=$daten[idbaustein];
	$z++;};



if(isset($haben_idbaustein)){$haben_idbaustein=array_unique($haben_idbaustein);}else{$haben_idbaustein[]="a";};
if(isset($haben_idreihenfolge)){$haben_idreihenfolge=array_unique($haben_idreihenfolge);}else{$haben_idreihenfolge[]="b";};
if(isset($haben_idtbaustein)){$haben_idtbaustein=array_unique($haben_idtbaustein);}else{$haben_idtbaustein[]="c";};
if(isset($haben_idbbaustein)){$haben_idbbaustein=array_unique($haben_idbbaustein);}else{$haben_idbbaustein[]="d";};
if(isset($haben_idgbaustein)){$haben_idgbaustein=array_unique($haben_idgbaustein);}else{$haben_idgbaustein[]="e";};
if(isset($haben_idlink)){$haben_idlink=array_unique($haben_idlink);}else{$haben_idlink[]="f";};
if(isset($haben_linkname)){$haben_linkname=array_unique($haben_linkname);}else{$haben_linkname[]="g";};
if(isset($soll_idlink)){$soll_idlink=array_unique($soll_idlink);}else{$soll_idlink[]="h";};
IF(ISSET($soll_idtbaustein)){$soll_idtbaustein=array_unique($soll_idtbaustein);}else{$soll_idtbaustein[]="i";};
if(isset($soll_idbbaustein)){$soll_idbbaustein=array_unique($soll_idbbaustein);}else{$soll_idbbaustein[]="j";};
if(isset($soll_idreihenfolge)){$soll_idreihenfolge=array_unique($soll_idreihenfolge);}else{$soll_idreihenfolge[]="k";};
if(isset($soll_idbaustein)){$soll_idbaustein=array_unique($soll_idbaustein);}else{$soll_idbaustein[]="l";};

$dlist=scandir(dokumente);
$blist=scandir(image);
$vlist=scandir(vorschau);

// die . und .. saetze aus skandir loeschen

unset($dlist[0]);
unset($dlist[1]);
unset($blist[0]);
unset($blist[1]);
unset($vlist[0]);
unset($vlist[1]);

// Ermitteln der Domainnamenlaenge
$sessionlaenge=strlen($_SESSION[website]);
$sessionlaengegesamt=$sessionlaenge+11;

// Formatieren der link-Datenbankeintraege auf die Form der Verzeichnisbenennung
foreach ($haben_linkname AS $nr)
	{if(substr($nr,0,$sessionlaenge)==$_SESSION[website])
		{$l_dok[]=substr($nr,$sessionlaengegesamt);
		};
	};

// loeschen der nicht verwendeten Dokumente
foreach($dlist AS $nr)
	{$ulkname="dokumente/".$nr;
	if(!in_array($nr,$l_dok)){unlink($ulkname);$_SESSION[fehler]=$_SESSION[fehler]." --Dokument geloescht: ".$nr;};
	};

// Formatieren der Bild-Datenbankeintraege
foreach ($haben_bildname AS $nr)
	{$l_bild[]=$nr.".png";
	};
		
// loeschen der nicht verwendeten Bilder
foreach($blist AS $nr)
	{$ulkname="image/".$nr;
	if(!in_array($nr,$l_bild)){unlink($ulkname);$_SESSION[fehler]=$_SESSION[fehler]." --Bild geloescht: ".$nr;};
	};
	
// loeschen der nicht verwendeten Vorschau-Bilder
foreach($vlist AS $nr)
	{$ulkname="vorschau/".$nr;
	if(!in_array($nr,$l_bild)){unlink($ulkname);$_SESSION[fehler]=$_SESSION[fehler]." --Vorschau geloescht: ".$nr;};
	};
	
// DB-Loeschlisten erstellen und loeschen
foreach($soll_idlink AS $nr)
	{if(!in_array($nr,$haben_idlink)){mysql_query("DELETE FROM link WHERE idlink='$nr'");
												$_SESSION[fehler]=$_SESSION[fehler]." --DB-Link geloescht: ".$nr;};	
	};
foreach($soll_idbbaustein AS $nr)
	{if(!in_array($nr,$haben_idbbaustein)){mysql_query("DELETE FROM bbaustein WHERE idbbaustein='$nr'");
														$_SESSION[fehler]=$_SESSION[fehler]." --Bildeintrag geloescht: ".$nr;};	
	};
foreach($soll_idreihenfolge AS $nr)
	{if(!in_array($nr,$haben_idreihenfolge)){mysql_query("DELETE FROM reihenfolge WHERE idreihenfolge='$nr'");
															$_SESSION[fehler]=$_SESSION[fehler]." --Reihenfolge geloescht: ".$nr;};
	};
foreach($soll_idbaustein AS $nr)
	{if(!in_array($nr,$haben_idbaustein)){mysql_query("DELETE FROM baustein WHERE idbaustein='$nr'");
														$_SESSION[fehler]=$_SESSION[fehler]." --Baustein geloescht: ".$nr;};
	};


?>
</body></html>
