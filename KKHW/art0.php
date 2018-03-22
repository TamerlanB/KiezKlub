<?php
session_start();
ob_start();
include("dbconnect.php");
$ip = getenv ( 'REMOTE_ADDR' );
$datum = date("Y-m-d H:i");

$uabfrage="SELECT * FROM user WHERE iduser='1'";
$uergebnis=mysql_query($uabfrage);
$udat=mysql_fetch_assoc($uergebnis);


// Bilder hochladen
if($_POST[upload]=="1")
	{
	$bname=$_POST[file];
	$bdaten=$_FILES[$bname];
	if(!isset($_POST[bbaustein]) OR $_POST[bbaustein]<"1")
		{$dbeintrag="INSERT INTO bbaustein (reihenfolge_idreihenfolge) VALUES ('$_POST[idreihenfolge]')";
		mysql_query($dbeintrag) or die ("bildanlage erfolglos");
		$id = "SELECT LAST_INSERT_ID() FROM bbaustein";
		mysql_query($id) or die ("datenbank erfolglos");
		$bildnr= mysql_insert_id();
		}else{$bildnr=$_POST[bbaustein];};
	$q_name=$bildnr;
	$qort="quellbild/".$q_name;
	$zort="image/".$bildnr.".png";
	$vort="vorschau/".$bildnr.".png";
	if(move_uploaded_file($_FILES[$bname][tmp_name], $qort)) 
		{chmod("$qort",0777);
		$_SESSION[bildnummer]=$bildnr;
	 	$q_wert=getimagesize($qort);
	 	$q_breite=current($q_wert);
	 	next($q_wert);
	  	$q_hoehe=current($q_wert);
	  	if($q_hoehe=="0"){$hoehe="1";}; 	
	  	$faktor=$q_breite/$q_hoehe;
  	 	$n_hoehe="600";
   	$n_breite=$n_hoehe*$faktor;
   	$v_hoehe="30";
   	$v_breite=$v_hoehe*$faktor;
		$q_type=$q_wert[mime];
   	if($q_type=="image/gif")
			{$altesbild=imagecreatefromgif($qort);};
	  	if($q_type=="image/png")
			{$altesbild=imagecreatefrompng($qort);};
		if($q_type=="image/jpeg")
			{$altesbild=imagecreatefromjpeg($qort);};
   		$neuesbild=imagecreatetruecolor($n_breite,$n_hoehe);
   		$vorschaubild=imagecreatetruecolor($v_breite,$v_hoehe);

			$farbe = imagecolorallocate($neuesbild, 255, 255, 255);			
			imagefill($neuesbild,0,0,$farbe);
			imagefill($vorschaubild,0,0,$farbe);
   		imagecopyresized($neuesbild,$altesbild,0,0,0,0,$n_breite,$n_hoehe,$q_breite,$q_hoehe);
   		imagecopyresized($vorschaubild,$altesbild,0,0,0,0,$v_breite,$v_hoehe,$q_breite,$q_hoehe);
	 	  	imagepng($neuesbild,$zort);
	 	  	chmod("$zort",0777);
	 	  	imagepng($vorschaubild,$vort);
	 	  	chmod("$vort",0777);
	 	  	unlink($qort);
	 	  	$z_wert=getimagesize($zort);
	 	  	next($z_wert);
	 	  	next($z_wert);
	 	  	next($z_wert);
	 		$z_masse=current($z_wert);
   	$bname=$bildnr;
   	$update="UPDATE bbaustein set bild='$bname', groesse='$z_masse' WHERE reihenfolge_idreihenfolge='$_POST[idreihenfolge]'";
   	mysql_query($update);
		};

	};

// Bildauswahl	
	if(isset($_POST[bildwahl]) AND $_POST[bildwahl]!="")	
		{if(!isset($_POST[bbaustein]) OR $_POST[bbaustein]<"1")
			{$dbeintrag="INSERT INTO bbaustein (reihenfolge_idreihenfolge,bild) VALUES ('$_POST[idreihenfolge]','$_POST[bildwahl]')";
			mysql_query($dbeintrag) or die ("bildanlage erfolglos");
			$id = "SELECT LAST_INSERT_ID() FROM bbaustein";
			mysql_query($id) or die ("datenbank erfolglos");
			$bildnr= mysql_insert_id();
			}else{$bildnr=$_POST[bbaustein];};
		};
	
// dokumente hochladen
if($_POST[d_upload]=="1")
	{move_uploaded_file($_FILES['dokument']['tmp_name'], "dokumente/".$_FILES['dokument']['name']); 
	$vort="dokumente/".$_FILES['dokument']['name'];
	chmod("$vort",0777);
	$_POST[linkwahl]=$_FILES['dokument']['name'];

	};
	
// Einpflegen der Eingabeaenderung im Bearbeitungsfenster
if($_POST[dpflege]=="1")
	{
	mysql_query("UPDATE baustein set name='$_POST[name]', zeile='$_POST[zeile]', spalte='$_POST[spalte]'  WHERE idbaustein='$_POST[idbaustein]'");
	mysql_query("UPDATE reihenfolge set reihenfolge='$_POST[reihenfolge]', zeilenumbruch='$_POST[zeilenumbruch]', zentriert='$_POST[zentriert]'  WHERE idreihenfolge='$_POST[idreihenfolge]'");
	if($_POST[aart]=="0" AND $_POST[text]!="")
								{$text=str_replace("'", "''",$_POST[text]);
								mysql_query("UPDATE tbaustein set reihenfolge_idreihenfolge=NULL WHERE reihenfolge_idreihenfolge='$_POST[idreihenfolge]'");
								mysql_query("UPDATE reihenfolge set textgroesse='$_POST[textgroesse]', fett='$_POST[fett]', kursiv='$_POST[kursiv]'  WHERE idreihenfolge='$_POST[idreihenfolge]'");
								mysql_query("UPDATE tbaustein set reihenfolge_idreihenfolge='$_POST[idreihenfolge]', name='$_POST[tname]', text='$text'  WHERE idtbaustein='$_POST[idtbaustein]'");
								};
	if($_POST[aart]=="1" AND isset($_SESSION[bnr]))
								{
								mysql_query("UPDATE bbaustein set reihenfolge_idreihenfolge=NULL WHERE reihenfolge_idreihenfolge='$_POST[idreihenfolge]'");
								mysql_query("UPDATE reihenfolge set vorschau='$_POST[vorschau]', bprozent='$_POST[bprozent]' WHERE idreihenfolge='$_POST[idreihenfolge]'");
								mysql_query("UPDATE bbaustein set reihenfolge_idreihenfolge='$_POST[idreihenfolge]',name='$_POST[bdname]' WHERE idbbaustein='$_POST[bbaustein]'");
								};
	if($_POST[aart]=="2" AND $_POST[linkziel]!="" AND $_POST[lname]!="")
								{mysql_query("UPDATE link set reihenfolge_idreihenfolge=NULL WHERE reihenfolge_idreihenfolge='$_POST[idreihenfolge]'");
								mysql_query("UPDATE reihenfolge set textgroesse='$_POST[textgroesse]', fett='$_POST[fett]', kursiv='$_POST[kursiv]' WHERE idreihenfolge='$_POST[idreihenfolge]'");
								mysql_query("UPDATE link set reihenfolge_idreihenfolge='$_POST[idreihenfolge]', name='$_POST[lname]', link='$_POST[linkziel]' WHERE idlink='$_POST[idlink]'");
								};
	if($_POST[aart]=="3" AND $_POST[gbnr]!=""){mysql_query("UPDATE gbaustein set reihenfolge_idreihenfolge=NULL WHERE reihenfolge_idreihenfolge='$_POST[idreihenfolge]'");
								mysql_query("UPDATE reihenfolge set idreihenfolge='$_POST[idreihenfolge]', textgroesse='$_POST[textgroesse]', fett='$_POST[fett]', kursiv='$_POST[kursiv]' WHERE idreihenfolge='$_POST[idreihenfolge]'");
								if($_POST[text]=="" AND $_POST[gbnr]!="")
									{mysql_query("UPDATE gbaustein set reihenfolge_idreihenfolge='$_POST[idreihenfolge]' WHERE idgbaustein='$_POST[gbnr]'");}
									else{
								mysql_query("UPDATE gbaustein set reihenfolge_idreihenfolge='$_POST[idreihenfolge]',name='$_POST[gbname]', text='$_POST[text]', abuse='$_POST[abuse]' WHERE idgbaustein='$_POST[gbnr]'");
										};
								};	
	if($_POST[aart]=="4" AND $_POST[text]!="")
								{$text=str_replace("'", "''",$_POST[text]);
								$adatum=$_POST[jahr]."-".$_POST[monat]."-".$_POST[tag]." ".$_POST[bstunde].":00";
								$edatum=$_POST[jahr]."-".$_POST[monat]."-".$_POST[tag]." ".$_POST[estunde].":00";
								$ldatum=mktime(0,0,0,$_POST[monat],$_POST[tag],$_POST[jahr])+$_POST[ldatum]*86400;							
								$ldatum=date("Y-m-d H:i",$ldatum);
							
								mysql_query("UPDATE baustein set a_datum='$adatum', e_datum='$edatum', l_datum='$ldatum' WHERE idbaustein='$_POST[idbaustein]'");
								mysql_query("UPDATE tbaustein set reihenfolge_idreihenfolge=NULL WHERE reihenfolge_idreihenfolge='$_POST[idreihenfolge]'");
								mysql_query("UPDATE reihenfolge set textgroesse='$_POST[textgroesse]', fett='$_POST[fett]', kursiv='$_POST[kursiv]'  WHERE idreihenfolge='$_POST[idreihenfolge]'");
								mysql_query("UPDATE tbaustein set reihenfolge_idreihenfolge='$_POST[idreihenfolge]', name='Termin', text='$text'  WHERE idtbaustein='$_POST[idtbaustein]'");
								};
						
	};

// Baustein loeschen
if($_POST[bloesch]=="1")
	{mysql_query("DELETE FROM baustein WHERE idbaustein='$_POST[bnr]'");
	mysql_query("DELETE FROM reihenfolge WHERE baustein_idbaustein='$_POST[bnr]'");
	};

// neuauswahl
if($_POST[bauswahl]=="n")
	{unset($_SESSION[auswahl]);
	unset($_SESSION[bauswahl]);
	unset($_SESSION[rauswahl]);
	unset($_POST[bauswahl]);
	unset($_SESSION[zeile]);
	unset($_SESSION[spalte]);
	unset($_SESSION[bname]);
	};
	
if($_POST[bauswahl]!="neu" AND isset($_POST[bauswahl]))
	{$bbergebnis=mysql_query("SELECT * FROM baustein WHERE idbaustein='$_POST[bauswahl]'");
	$bd=mysql_fetch_assoc($bbergebnis);
	$_SESSION[zeile]=$bd[zeile];
	$_SESSION[spalte]=$bd[spalte];
	$_SESSION[bname]=$bd[name];
	$_SESSION[bnr]=$_POST[bauswahl];
	};

if(isset($_POST[rauswahl]))
	{$_SESSION[rauswahl]=$_POST[rauswahl];};
	
if(isset($_POST[bauswahl]))
	{$_SESSION[bauswahl]=$_POST[bauswahl];};
	
if(isset($_POST[auswahl]))
	{if($_POST[bname]=="" OR $_POST[zeile]=="" OR $_POST[spalte]=="")
		{$fehler="Daten Neuanlage unvollstaendig";}
		else{
		$_SESSION[auswahl]=$_POST[auswahl];
		$_SESSION[bname]=$_POST[bname];
		$_SESSION[zeile]=$_POST[zeile];
		$_SESSION[spalte]=$_POST[spalte];
		mysql_query("INSERT INTO baustein (menu_idmenu,name,zeile,spalte) VALUES ('$_POST[menunr]','$_POST[bname]','$_POST[zeile]','$_POST[spalte]')");	
		$id = "SELECT LAST_INSERT_ID() FROM baustein";
		mysql_query($id);
		$_SESSION[bnr]=mysql_insert_id();
		
		mysql_query("INSERT INTO reihenfolge (baustein_idbaustein,reihenfolge,aart) VALUES ('$_SESSION[bnr]','0','$_POST[auswahl]')");	
		$id = "SELECT LAST_INSERT_ID() FROM reihenfolge";
		mysql_query($id);
		$_SESSION[rauswahl]=mysql_insert_id();
		
		};
	};

// Passwortanzeige
if($_POST[menuanz]=="1")
	{$_SESSION[menuanz]="1";};
if($_POST[menuanz]=="0")
	{unset($_SESSION[menuanz]);};

// Untermenuaenderung
if($_POST[umenuaend]=="1" AND $_POST[umenuaenbest]=="1")
	{mysql_query("UPDATE menu set hmstellung='$_SESSION[hmenustellung]' WHERE idmenu='$_POST[umu]'");
	};


// Menuaenderung
if($_POST[menuaenderung]=="1")
	{$hmaergebnis=mysql_query("SELECT * FROM menu WHERE idmenu='$_POST[menunr]'");
	$hmadaten=mysql_fetch_assoc($hmaergebnis);

	if($_POST[hmstellung]!=$hmadaten[hmstellung])
		{$ergebnis=mysql_query("SELECT * FROM menu WHERE hmstellung='$hmadaten[hmstellung]'");
		$hmazahl=mysql_num_rows($ergebnis);

// nachfolgende Datensaetze suchen
		$fergebnis=mysql_query("SELECT * FROM menu WHERE hmstellung>='$_POST[hmstellung]' ORDER BY hmstellung, stellung");
		$fzahl=mysql_num_rows($fergebnis);
		$zzz="0";
		WHILE($zzz<$fzahl)
			{$fdaten=mysql_fetch_assoc($fergebnis);
			$nnr=$fdaten[hmstellung]+$_POST[hmstellung];
			mysql_query("UPDATE menu set hmstellung='$nnr' WHERE idmenu='$fdaten[idmenu]'"); 
			$zzz++;};
		
		$z="0";
		WHILE ($z<$hmazahl)
			{$hmedaten=mysql_fetch_assoc($ergebnis);
			mysql_query("UPDATE menu set hmstellung='$_POST[hmstellung]' WHERE idmenu='$hmedaten[idmenu]'");
			$z++;};
		};
	
	
	mysql_query("UPDATE menu set menu='$_POST[menu]',untermenu='$_POST[umenu]', stellung='$_POST[stellung]',ueberschrift='$_POST[ueberschrift]'
	 WHERE idmenu='$_POST[menunr]'");
	 $rfergebnis=mysql_query("SELECT * FROM menu ORDER BY hmstellung, stellung, timestamp DESC");
	 $rfzahl=mysql_num_rows($rfergebnis);
	 $z="0";
	 $umeintrag="0";
	 $ummerk="0";
	 $hmeintrag="0";
	 WHILE ($z<$rfzahl)
	 	{$rfdaten=mysql_fetch_assoc($rfergebnis);
	 	if(!isset($hmmerk) OR $rfdaten[hmstellung]>$hmmerk)
	 		{$hmmerk=$rfdaten[hmstellung];$hmeintrag++;$umeintrag="0";$ummerk="0";};
		mysql_query("UPDATE menu set hmstellung='$hmeintrag', stellung='$umeintrag' WHERE idmenu='$rfdaten[idmenu]'");
		$umeintrag++;
	 	$z++;};
	 
	 
	 };


// Menuloeschung
if(isset($_POST[menuloesch]) AND $_POST[lbest]=="1")
	{echo "Affe";
	$lergebnis=mysql_query("SELECT * FROM menu JOIN baustein ON menu.idmenu='$_POST[menunr]' AND baustein.menu_idmenu=menu.idmenu");
	$lzahl=mysql_num_rows($lergebnis);
	$zahl="0";
	while($zahl<$lzahl)
		{$ldat=mysql_fetch_assoc($lergebnis);
		$lreihenfolge=$ldat[idbaustein];
		mysql_query("DELETE FROM reihenfolge WHERE baustein_idbaustein='$lreihenfolge'");
		mysql_query("DELETE FROM baustein WHERE idbaustein='$lreihenfolge'");
		$zahl++;};
	mysql_query("DELETE FROM menu WHERE idmenu='$_POST[menunr]'");
	};

if($_GET[menunr]!="" OR $_POST[menunr]!="")
	{$menunr=$_GET[menunr];
	if(isset($_POST[menunr])){$menunr=$_POST[menunr];};
	$mabfrage="SELECT * from menu where idmenu='$menunr'";
	$menuergebnis=mysql_query($mabfrage) or die ("Menuabfrage erfolglos");
	$menudaten=mysql_fetch_assoc($menuergebnis);
	$_SESSION[menunr]=$menudaten[idmenu];
	$_SESSION[seite]="0";
	$_SESSION[menustellung]=round($menudaten[stellung]);
	$_SESSION[hmenustellung]=round($menudaten[hmstellung]);
	};
// bausteinbearbeitung
	$bggergebnis=mysql_query("SELECT * FROM baustein WHERE menu_idmenu='$menunr' ORDER BY zeile, spalte");
	$bggzahl=mysql_num_rows($bggergebnis);
	$z="0";
	$zeile="0";
	$spalte="0";
	WHILE($z<$bggzahl)
		{$daten=mysql_fetch_assoc($bggergebnis);
		if($daten[zeile]>$zeile){$zeile=$daten[zeile];};
		if($daten[spalte]>$spalte){$spalte=$daten[spalte];};
		$liste[]="Z: ".$daten[zeile]." Sp: ".$daten[spalte];
		$z++;};
		
// Ermitteln der verwaisten Untermenues und erstellung entspr DB-Eintraege
$hmergebnis=mysql_query("SELECT * FROM menu WHERE stellung='0'");
$hmzahl=mysql_num_rows($hmergebnis);
$z="0";
WHILE ($z<$hmzahl)
	{$hmdaten=mysql_fetch_assoc($hmergebnis);
	$hmd[]=$hmdaten[hmstellung];
	$z++;};	

$hmergebnis=mysql_query("SELECT * FROM menu WHERE stellung!='0'");
$hmzahl=mysql_num_rows($hmergebnis);
$z="0";
WHILE ($z<$hmzahl)
	{$hmdaten=mysql_fetch_assoc($hmergebnis);
	$umd[]=$hmdaten[hmstellung];
	$z++;};				

if(isset($hmd)){$hmd=array_unique($hmd);};
if(isset($umd)){$umd=array_unique($umd);};
if(isset($nds)){$nds=array_diff($umd,$hmd);};

if(count($nds)>"0")
	{mysql_query("INSERT INTO menu (menu,hmstellung,stellung) VALUES ('n. zugeordnet','99','0')");
	foreach($nds AS $nr)
		{
		mysql_query("UPDATE menu set hmstellung='99' WHERE hmstellung='$nr'");
		};
	};
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html><head>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
<link href="stylesheet.css" rel="stylesheet" type="text/css">

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
<?php 

echo "<font color='red'>", $fehler, "<p></p></font>";
unset($fehler);



if($_SESSION[admin]=="1")
	{if(!isset($_SESSION[menuanz]))
		{
// Menubearbeitung
		?>
		<center>
		<table width="750"><tr><td>
		<font size="3">
		<form style="margin:0;" name="menupflege" action="<?PHP $PHP_SELF = $_SERVER['PHP_SELF']; echo $PHP_SELF; ?>" method="post" target="_parent">
		<?php		
		if($_SESSION[menustellung]=="0")
			{ ?>
			<center>Men&uuml;: <input type="text" name="menu" size="15" value="<?php echo $menudaten[menu]; ?>">
			</td><td><center>
			Stellung: <input type="text" name="hmstellung" size="1" value="<?php echo $menudaten[hmstellung]; ?>">
			<input type="hidden" name="umenu" value="<?php echo $menudaten[untermenu]; ?>">
			<input type="hidden" name="stellung" value="<?php echo $menudaten[stellung]; ?>">
			<?php
			}else{
			?>	
			</td><td><center>
			Untermen&uuml;: <input type="text" name="umenu" size="15" value="<?php echo $menudaten[untermenu]; ?>">
			</td><td><center>
			Stellung: <input type="text" name="stellung" size="1" value="<?php echo $menudaten[stellung]; ?>">
			<input type="hidden" name="menu" value="<?php echo $menudaten[menu]; ?>">
			<input type="hidden" name="hmstellung" value="<?php echo $menudaten[hmstellung]; ?>">
			<?php
			};
			?>
		</td><td><center>
		&Uuml;berschrift: <input type="text" name="ueberschrift" size="17" value="<?php echo $menudaten[ueberschrift]; ?>">
		</td><td><center>
		<input type="hidden" name="menunr" value="<?php echo $menunr; ?>">
		<input type="hidden" name="menuaenderung" value="1">
		<input type="submit" id="sbutton" class="sd" name="senden" value="&Auml;ndern" />
		</form>
		</td><td><center>
		<form style="margin:0;" name="menuloesch" action="<?PHP $PHP_SELF = $_SERVER['PHP_SELF']; echo $PHP_SELF; ?>" method="post" target="_parent">
		<input type="hidden" name="menunr" value="<?php echo $menunr; ?>">
		<input type="hidden" name="menuloesch" value="1">
		<input type="submit" id="sbutton" class="sd" name="senden" value="L&ouml;schen" />
		</td><td><center>
		<input type="checkbox" name="lbest" value="1">
		</form>
		</td><td><center>
		<form style="margin:0;" name="menuverbergen" action="<?PHP $PHP_SELF = $_SERVER['PHP_SELF']; echo $PHP_SELF; ?>" method="post" target="_parent">
		<input type="hidden" name="menuanz" value="1">
		<input type="hidden" name="menunr" value="<?php echo $menunr; ?>">
		<input type="submit" id="sbutton" class="sd" name="senden" value="Ausblenden" />
		</form>
		</font>
		</td></tr></table>

		<?php

// Untermenues zuordnung aendern
	if($_SESSION[menustellung]=="0")
		{
		$uuergebnis=mysql_query("SELECT * FROM menu WHERE hmstellung!='$_SESSION[hmenustellung]' AND stellung!='0'");
		$uuzahl=mysql_num_rows($uuergebnis);
		?>
		<form style="margin:0;" name="menupflege" action="<?PHP $PHP_SELF = $_SERVER['PHP_SELF']; echo $PHP_SELF; ?>" method="post" target="_parent">
		<font size="3">fremdes Untermen&uuml; :</font>
		<input type="hidden" name="umenuaend" value="1">
		<input type="hidden" name="menu" value="<?php echo $menudaten[menu]; ?>">
		<input type="hidden" name="hmstellung" value="<?php echo $menudaten[hmstellung]; ?>">
		<input type="hidden" name="menunr" value="<?php echo $menunr; ?>">
		<select name="umu" size="1" >
		<?php
		$z="0";
		WHILE($z<$uuzahl)
			{$udaten=mysql_fetch_assoc($uuergebnis);
			?>
			<option value="<?php echo $udaten[idmenu]; ?>"><?php echo $udaten[untermenu]; ?>  </option>
			<?php
			$z++;};
			?>	
		</select>
		<input type="checkbox" name="umenuaenbest" value="1">
		<input type="submit" id="sbutton" class="sd" name="senden" value=" &uuml;bernehmen" />
		<?php
		};
	};
		
	};
	



if($_SESSION[admin]=="1")
	{
	$babfrage="SELECT * FROM baustein WHERE  menu_idmenu='$menunr' ORDER BY zeile, spalte";
	$bergebnis=mysql_query($babfrage);
	$banzahl=mysql_num_rows($bergebnis);



// Tabelle eroeffnen
	?>		
	<center><p></p><table border="1" cellspacing="2" cellpadding="2"><tr><td align="center">

	<?php

	
// bausteinbearbeitung
	$bggergebnis=mysql_query("SELECT * FROM baustein WHERE menu_idmenu='$menunr' ORDER BY zeile, spalte");
	$bggzahl=mysql_num_rows($bggergebnis);
	$z="0";
	$zeile="0";
	$spalte="0";
	WHILE($z<$bggzahl)
		{$daten=mysql_fetch_assoc($bggergebnis);
		if($daten[zeile]>$zeile){$zeile=$daten[zeile];};
		if($daten[spalte]>$spalte){$spalte=$daten[spalte];};
		$liste[]="Z: ".$daten[zeile]." Sp: ".$daten[spalte];
		$z++;};	
		
// bausteinauswahlanzeige
	if(!isset($_SESSION[rauswahl]))
		{ ?>
		Baustein:<br>
		<form style="margin:0;" name="bsauswahl" action="<?PHP $PHP_SELF = $_SERVER['PHP_SELF']; echo $PHP_SELF; ?>" method="post" target="_parent">
		<select name="bauswahl" size="4" style=width:350px; onChange="document.bsauswahl.submit()" >
		<?php
		$z="0";
		WHILE($z<$banzahl)
			{$bdaten=mysql_fetch_assoc($bergebnis);
			if($_POST[bauswahl]==$bdaten[idbaustein]){$checked="selected";}else{$checked="";};
			?>
			<option <?php echo $checked; ?> value="<?php echo $bdaten[idbaustein]; ?>"><?php echo $bdaten[name]," - Zeile: ",$bdaten[zeile]," Spalte: ",$bdaten[spalte]; ?>  </option>
			<?php
			$z++;};
			?>	
		<option value="neu" >Neu</option>		
		</select>
		<input type="hidden" name="menunr" value="<?php echo $menunr; ?>">
		<input type="hidden" name="ueberschrift" value="<?php echo $daten[ueberschrift]; ?>">
		<br>
		<input type="submit" id="sbutton" class="sd" style=width:110px; name="senden" value="Dazu / &Auml;ndern" />
		</form>

		<?php
		};

// Vergabe typ (auswahl) des neuen eintrags	 
	if($_SESSION[bauswahl]=="neu") 
		{ if(!isset($_SESSION[auswahl]))
			{	
			?>
			<table border="1"><tr><td>
			<center>Baustein-Neuanlage:
			<form style="margin:0;" name="artstellung" action="<?PHP $PHP_SELF = $_SERVER['PHP_SELF']; echo $PHP_SELF; ?>" method="post" target="_parent">
			Zeile : <input type="text" size="1" value=<?php echo $zeile+1; ?> name="zeile">
			Spalte:<input type="text" size="1" name="spalte"><br>
			Name:<input type="text" size="15" name="bname"><br>
			<center>
			Typ:<select name="auswahl" size="1" style=width:150px; onChange="document.neuanlage.submit()" >
			<option value="0" >Text</option>
			<option value="1" >Bild</option>
			<option value="2" >Link</option>
			<option value="3" >GB-Artikel</option>
			<option value="4" >Termin</option>
			</select>
			<input type="hidden" name="menunr" value="<?php echo $menunr; ?>">
			<input type="hidden" name="ueberschrift" value="<?php echo $daten[ueberschrift]; ?>">
			<br>
			<input type="submit" id="sbutton" class="sd" style=width:110px; name="senden" value="Dazu" />
			</form>
			</td><td>
			</td></tr></table>		
		<?php
			};
		};
	
	if(isset($_SESSION[bauswahl]) AND isset($_SESSION[bnr]) AND !isset($_SESSION[rauswahl]) AND $_SESSION[bauswahl]!="neu")
		{if(isset($_SESSION[auswahl]))
		{$rabfrage="SELECT * FROM reihenfolge WHERE baustein_idbaustein='$_SESSION[bnr]' AND aart='$_SESSION[auswahl]'";}
		else{$rabfrage="SELECT * FROM reihenfolge WHERE baustein_idbaustein='$_SESSION[bnr]'";};
		$rergebnis=mysql_query($rabfrage);
		$rzahl=mysql_num_rows($rergebnis);
		$z="0";
		?>
		</td><td>
		<center>Art / Reihenfolge:
		<br>
		<form style="margin:0;" name="anzeige" action="<?PHP $PHP_SELF = $_SERVER['PHP_SELF']; echo $PHP_SELF; ?>" method="post" target="_parent">
		<?php
		WHILE($z<$rzahl)
			{$rdaten=mysql_fetch_assoc($rergebnis);
			if($rdaten[aart]=="4"){$art="Termin";};
			if($rdaten[aart]=="3"){$art="G&auml;stebuch";};
			if($rdaten[aart]=="2"){$art="Link";};
			if($rdaten[aart]=="1"){$art="Bild";};
			if($rdaten[aart]=="0"){$art="Text";};
	
			echo "<sbutton>Reihen-ID: ",$rdaten[idreihenfolge], " Reihenfolge: ",$rdaten[reihenfolge]," Art: ",$art,"</sbutton><br>";

			$z++;};
		?>
		<input type="hidden" name="rauswahl" value="<?php echo $rdaten[idreihenfolge]; ?>">
		<input type="hidden" name="menunr" value="<?php echo $menunr; ?>">
		<br>
		<input type="submit" id="sbutton" class="sd" style=width:120px; name="senden" value="&Auml;ndern" />
		</form>
		<p></p>
		<form style="margin:0;" name="bloesch" action="<?PHP $PHP_SELF = $_SERVER['PHP_SELF']; echo $PHP_SELF; ?>" method="post" target="_parent">
		<input type="hidden" name="menunr" value="<?php echo $menunr; ?>">
		<input type="hidden" name="bnr" value="<?php echo $_SESSION[bnr]; ?>">
		<input type="hidden" name="ueberschrift" value="<?php echo $daten[ueberschrift]; ?>">
		<input type="hidden" name="bloesch" value="1">
		<input type="submit" id="sbutton" class="sd" style=width:120px; name="senden" value="Baustein L&ouml;schen" />	
		</form>
		<?php
		};

// Gemeinsamer Kopf
	if(isset($_SESSION[rauswahl]))
		{$abfrage="SELECT * FROM reihenfolge JOIN (baustein,menu) ON idreihenfolge='$_SESSION[rauswahl]' AND reihenfolge.baustein_idbaustein=baustein.idbaustein
		AND baustein.menu_idmenu=menu.idmenu";
		$ergebnis=mysql_query($abfrage);
		$daten=mysql_fetch_assoc($ergebnis);

		?>
		<center><table border="1" width="800"><tr><td align="right"  valign="top" width="300">

		<form style="margin:0;" name="anzeige" action="<?PHP $PHP_SELF = $_SERVER['PHP_SELF']; echo $PHP_SELF; ?>" method="post" target="_parent">
		<input type="hidden" name="dpflege" value="1">
		<input type="hidden" name="menunr" value="<?php echo $menunr; ?>">
		<input type="hidden" name="idbaustein" value="<?php echo $daten[idbaustein]; ?>">
		<input type="hidden" name="ueberschrift" value="<?php echo $daten[ueberschrift]; ?>">
		<input type="hidden" name="idreihenfolge" value="<?php echo $daten[idreihenfolge]; ?>">
		<input type="hidden" name="idtbaustein" value="<?php echo $daten[idtaustein]; ?>">
		<input type="hidden" name="idbbaustein" value="<?php echo $daten[idbbaustein]; ?>">
		<input type="hidden" name="idlink" value="<?php echo $daten[idlink]; ?>">
		<input type="hidden" name="idgbaustein" value="<?php echo $daten[idgbaustein]; ?>">
		<input type="hidden" name="idmenu" value="<?php echo $daten[idmenu]; ?>">
		<input type="hidden" name="aart" value="<?php echo $daten[aart]; ?>">
		B-Name: <input type="text" size="25" name="name" value="<?php echo $daten[name]; ?>">
		<br>Zeile:<input type="text" size="1" name="zeile" value="<?php echo $daten[zeile]; ?>">
		Spalte:<input type="text" size="1" name="spalte" value="<?php echo $daten[spalte]; ?>">
		Reihe:<input type="text" size="1" name="reihenfolge" value="<?php echo $daten[reihenfolge]; ?>">
		<br>Z-Umbruch danach:<input type="checkbox" value="1" size="1" name="zeilenumbruch" <?php if($daten[zeilenumbruch]=="1"){echo "checked";}; ?>>
		Zentriert:<input type="checkbox" size="1" value="1" name="zentriert" <?php if($daten[zentriert]=="1"){echo "checked";}; ?>>	
		</td><td align="right">


		<?php
// Textbaustein
		if($daten[aart]=="0")
			{
			$kergebnis=mysql_query("SELECT * FROM tbaustein WHERE reihenfolge_idreihenfolge='$daten[idreihenfolge]'");
			$kzahl=mysql_num_rows($kergebnis);
			if($kzahl=="0")
				{mysql_query("INSERT INTO tbaustein (reihenfolge_idreihenfolge) VALUES ('$daten[idreihenfolge]')") or die ("dbweg");};
			$kergebnis=mysql_query("SELECT * FROM tbaustein WHERE reihenfolge_idreihenfolge='$daten[idreihenfolge]'");
			$kzahl=mysql_num_rows($kergebnis);
			$kdaten=mysql_fetch_assoc($kergebnis);
			if($kdaten[name]==NULL OR $kdaten[name]==""){$kdaten[name]=$daten[name];};
			?>
			<input type="hidden" name="idtbaustein" value="<?php echo $kdaten[idtbaustein]; ?>">
			Textname:<input type="text" size="8" name="tname" value="<?php echo $kdaten[name]; ?>">
			<br>Textgr&ouml;sse:<input type="text" size="8" name="textgroesse" value="<?php echo $daten[textgroesse]; ?>">
			<br>Fett:<input type="checkbox" size="1" value="1" name="fett" <?php if($daten[fett]=="1"){echo "checked";}; ?>>	
			Kursiv:<input type="checkbox" size="1" value="1" name="kursiv" <?php if($daten[kursiv]=="1"){echo "checked";}; ?>>
			</td></tr></table>
			</td></tr><tr><td>
			<textarea name="text" rows="5" cols="111" ><?php echo $kdaten[text]; ?></textarea>
			</td></tr><tr><td>	
			<center><input type="submit" id="sbutton" class="sd" style=width:120px; name="senden" value="&Auml;ndern" />	
			</form>
			<?php
			};

// Bildbaustein
		if($daten[aart]=="1")
			{$kergebnis=mysql_query("SELECT * FROM bbaustein WHERE reihenfolge_idreihenfolge='$daten[idreihenfolge]'");
			$kzahl=mysql_num_rows($kergebnis);
			if($kzahl=="0")
				{mysql_query("INSERT INTO bbaustein (reihenfolge_idreihenfolge) VALUES ('$daten[idreihenfolge]'");};
			$kergebnis=mysql_query("SELECT * FROM bbaustein WHERE reihenfolge_idreihenfolge='$daten[idreihenfolge]'");
			$kzahl=mysql_num_rows($kergebnis);
			$kdaten=mysql_fetch_assoc($kergebnis);
			if($kdaten[bild]==""){$anzeig="Kein Bild vorhanden<br>erst hochladen dann senden";}else{$anzeig="Bild vorhanden, Updaten?";
										?>
										<input type="hidden" name="abild" value="<?php echo $kdaten[bild]; ?>">
										<?php										
										};
			if($kdaten[name]==NULL OR $kdaten[name]==""){$kdaten[name]=$daten[name];};	

			?>
			<input type="hidden" name="bbaustein" value="<?php echo $kdaten[idbbaustein]; ?>">
			Bildname: <input type="text" size="12" name="bdname" value="<?php echo $kdaten[name]; ?>">
			<br>
			Bild % -Darstellung: <input type="text" size="2" name="bprozent" value="<?php echo $daten[bprozent]; ?>">
			<br>Vorschau auf Seite:<input type="checkbox" value="1" size="1" name="vorschau" <?php if($daten[vorschau]=="1"){echo "checked";}; ?>>	
			</td><td>
			<?php echo "<font color='red'>", $anzeig; ?>			
			</td></tr></table>
			<center><input type="submit" id="sbutton" class="sd" style=width:120px; name="senden" value="&Auml;ndern" />	
			</form>
			<?php
// Bildauswahl erstellen
			if($kdaten[bild]=="")
				{$blist=scandir(image);
				?>
				<center>	Oder:			
				<form style="margin:0;" name="b_auswahl" action="<?PHP $PHP_SELF = $_SERVER['PHP_SELF']; echo $PHP_SELF; ?>" method="post" target="_parent">
				<input type="hidden" name="menunr" value="<?php echo $menunr; ?>">
				<input type="hidden" name="idbaustein" value="<?php echo $daten[idbaustein]; ?>">
				<input type="hidden" name="idreihenfolge" value="<?php echo $daten[idreihenfolge]; ?>">
				<input type="hidden" name="idtbaustein" value="<?php echo $daten[idtaustein]; ?>">
				<input type="hidden" name="idbbaustein" value="<?php echo $daten[idbbaustein]; ?>">
				<input type="hidden" name="idlink" value="<?php echo $daten[idlink]; ?>">
				<input type="hidden" name="idgbaustein" value="<?php echo $daten[idgbaustein]; ?>">
				<input type="hidden" name="idmenu" value="<?php echo $daten[idmenu]; ?>">
				<input type="hidden" name="aart" value="<?php echo $daten[aart]; ?>">
				<input type="hidden" name="bbaustein" value="<?php echo $kdaten[idbbaustein]; ?>">
			
				<select name="bildwahl" size="0" onChange="document.b_auswahl.submit()" >
				<?php
				foreach($blist as $bnr)
					{if($bnr!="." AND $bnr!="..")
						{echo "<option>",substr($bnr,0,-4),"</option>";
						};
					};
				?>				
				</select>
				<input type="submit" id="sbutton" class="sd" style=width:150px; name="senden" value="Bild w&auml;hlen" />	
				</form>
				<center>Oder:
				<?php	
				};
				?>					
			</td></tr><tr><td>

				
<! uploadformular>
			<center>
			<form style="margin:0;" action="<?PHP $PHP_SELF = $_SERVER['PHP_SELF']; echo $PHP_SELF; ?>" method="post" target="_parent" enctype="multipart/form-data">
			<input type="hidden" name="menunr" value="<?php echo $menunr; ?>">
			<input type="hidden" name="idbaustein" value="<?php echo $daten[idbaustein]; ?>">
			<input type="hidden" name="idreihenfolge" value="<?php echo $daten[idreihenfolge]; ?>">
			<input type="hidden" name="idtbaustein" value="<?php echo $daten[idtaustein]; ?>">
			<input type="hidden" name="idbbaustein" value="<?php echo $daten[idbbaustein]; ?>">
			<input type="hidden" name="idlink" value="<?php echo $daten[idlink]; ?>">
			<input type="hidden" name="idgbaustein" value="<?php echo $daten[idgbaustein]; ?>">
			<input type="hidden" name="idmenu" value="<?php echo $daten[idmenu]; ?>">
			<input type="hidden" name="aart" value="<?php echo $daten[aart]; ?>">
			<input type="hidden" name="bbaustein" value="<?php echo $kdaten[idbbaustein]; ?>">
			<center>
			<input type="hidden" name="upload" value="1" />
			<input type="hidden" name="idreihenfolge" value="<?php echo $daten[idreihenfolge]; ?>">
			<input type="hidden" name="bbaustein" value="<?php echo $kdaten[idbbaustein]; ?>">
<! hier wird der name des festgelegt, wie dieser bei der nachfolgenden bearbeitung benannt wird>	
			<input type="hidden" name="file" value="bild" />
			<input type="file" name="bild" />
			<input type="submit" id="sbutton" value="Bild hochladen" />
			</form>
			<?php
			if($kdaten[bild]=="")
				{ ?>
				</td></tr><tr><td>
				<table border="1" width="100%"><tr><td>
				<?php
				reset ($blist);
				$zl="1";
				foreach($blist as $bnr)
					{if($bnr!="." AND $bnr!="..")
						{if($zl/4==round($zl/4)){$ws="</td></tr><tr><td>";}else{$ws="</td><td>";};
						$banz="<a href='image/".$bnr."' target='_blank' ><img src='vorschau/".$bnr."' alt='Bild gestoert' /></a>";
						echo "<center>",$bnr,"<br>",$banz,$ws;
						$zl++;
						};
					};
				echo "</td></tr></table>";				
				};

			};

// Link einfuegen
		if($daten[aart]=="2")
			{
			$kergebnis=mysql_query("SELECT * FROM link WHERE reihenfolge_idreihenfolge='$daten[idreihenfolge]'");
			$kzahl=mysql_num_rows($kergebnis);
			if($kzahl=="0")
				{mysql_query("INSERT INTO link (reihenfolge_idreihenfolge) VALUES ('$daten[idreihenfolge]')") or die ("dbweg");};
			$kergebnis=mysql_query("SELECT * FROM link WHERE reihenfolge_idreihenfolge='$daten[idreihenfolge]'");
			$kzahl=mysql_num_rows($kergebnis);
			$kdaten=mysql_fetch_assoc($kergebnis);
			if($kdaten[name]==NULL OR $kdaten[name]==""){$kdaten[name]=$daten[name];};

		if(isset($_POST[linkwahl]) AND $_POST[linkwahl]!="")	
			{$kdaten[link]=$_SESSION[website]."/dokumente/".$_POST[linkwahl];};		
// Dokumentendirectory auslesen
			$dlist=scandir(dokumente);

		
			?>

			<input type="hidden" name="idlink" value="<?php echo $kdaten[idlink]; ?>">
			Ziel:<input type="text" size="53" maxlength="120" name="linkziel" value="<?php echo $kdaten[link]; ?>">
			<br>Text:<input type="text" size="53" maxlength="500" name="lname" value="<?php echo nl2br($kdaten[name]); ?>">
			<br>Textgr&ouml;sse:<input type="text" size="1" name="textgroesse" value="<?php echo $daten[textgroesse]; ?>">
			Fett:<input type="checkbox" size="1" value="1" name="fett" <?php if($daten[fett]=="1"){echo "checked";}; ?>>	
			Kursiv:<input type="checkbox" size="1" value="1" name="kursiv" <?php if($daten[kursiv]=="1"){echo "checked";}; ?>>			

			</td></tr></table>
			<center><input type="submit" id="sbutton" class="sd" style=width:120px; name="senden" value="&Auml;ndern" />	
			</form>
			<br>Oder:
			<form style="margin:0;" name="anzeige" action="<?PHP $PHP_SELF = $_SERVER['PHP_SELF']; echo $PHP_SELF; ?>" method="post" target="_parent">
			<input type="hidden" name="dpflege" value="1">
			<input type="hidden" name="menunr" value="<?php echo $menunr; ?>">
			<input type="hidden" name="idbaustein" value="<?php echo $daten[idbaustein]; ?>">
			<input type="hidden" name="idreihenfolge" value="<?php echo $daten[idreihenfolge]; ?>">
			<input type="hidden" name="idtbaustein" value="<?php echo $daten[idtbaustein]; ?>">
			<input type="hidden" name="idbbaustein" value="<?php echo $daten[idbbaustein]; ?>">
			<input type="hidden" name="idlink" value="<?php echo $daten[idlink]; ?>">
			<input type="hidden" name="idgbaustein" value="<?php echo $daten[idgbaustein]; ?>">
			<input type="hidden" name="idmenu" value="<?php echo $daten[idmenu]; ?>">
			<input type="hidden" name="aart" value="<?php echo $daten[aart]; ?>">
			<input type="hidden" name="name" value="<?php echo $daten[name]; ?>">
			<input type="hidden" name="zeile" value="<?php echo $daten[zeile]; ?>">
			<input type="hidden" name="spalte" value="<?php echo $daten[spalte]; ?>">
			<input type="hidden" name="zeilenumbruch" value="<?php echo $daten[zeilenumbruch]; ?>">
			<input type="hidden" name="zentriert" value="<?php echo $daten[zentriert]; ?>">
			<input type="hidden" name="lname" value="<?php echo $kdaten[name]; ?>">
			<input type="hidden" name="textgroesse" value="<?php echo $daten[textgroesse]; ?>">
			<input type="hidden" name="fett" value="<?php echo $daten[fett]; ?>">
			<input type="hidden" name="kursiv" value="<?php echo $daten[kursiv]; ?>">
			<input type="hidden" name="d_suchen" value="1">
			<input type="submit" id="sbutton" class="sd" style=width:150px; name="senden" value="Dokument suchen" />	
			</form>
			</td></tr><tr><td width="500">
<?php
		if($_POST[d_suchen]=="1")
				{ ?>
				<center>				
				<form style="margin:0;" name="d_auswahl" action="<?PHP $PHP_SELF = $_SERVER['PHP_SELF']; echo $PHP_SELF; ?>" method="post" target="_parent">
				<input type="hidden" name="dpflege" value="1">
				<input type="hidden" name="menunr" value="<?php echo $menunr; ?>">
				<input type="hidden" name="idbaustein" value="<?php echo $daten[idbaustein]; ?>">
				<input type="hidden" name="idreihenfolge" value="<?php echo $daten[idreihenfolge]; ?>">
				<input type="hidden" name="idtbaustein" value="<?php echo $daten[idtbaustein]; ?>">
				<input type="hidden" name="idbbaustein" value="<?php echo $daten[idbbaustein]; ?>">
				<input type="hidden" name="idlink" value="<?php echo $daten[idlink]; ?>">
				<input type="hidden" name="idgbaustein" value="<?php echo $daten[idgbaustein]; ?>">
				<input type="hidden" name="idmenu" value="<?php echo $daten[idmenu]; ?>">
				<input type="hidden" name="aart" value="<?php echo $daten[aart]; ?>">
				<input type="hidden" name="name" value="<?php echo $daten[name]; ?>">
				<input type="hidden" name="zeile" value="<?php echo $daten[zeile]; ?>">
				<input type="hidden" name="spalte" value="timest<?php echo $daten[spalte]; ?>">
				<input type="hidden" name="zeilenumbruch" value="<?php echo $daten[zeilenumbruch]; ?>">
				<input type="hidden" name="zentriert" value="<?php echo $daten[zentriert]; ?>">
				<input type="hidden" name="lname" value="<?php echo $kdaten[name]; ?>">
				<input type="hidden" name="textgroesse" value="<?php echo $daten[textgroesse]; ?>">
				<input type="hidden" name="fett" value="<?php echo $daten[fett]; ?>">
				<input type="hidden" name="kursiv" value="<?php echo $daten[kursiv]; ?>">

				<select name="linkwahl" size="0" onChange="document.d_auswahl.submit()" >
				<?php
				foreach($dlist as $dnr)
					{if($dnr!="." AND $dnr!="..")
						{echo "<option>",$dnr,"</option>";
						};
					};
				?>				
				</select>
				<input type="submit" id="sbutton" class="sd" style=width:150px; name="senden" value="Dokument w&auml;hlen" />	
			</form>

				<?php
				}else
				{
				?>
<! uploadformular>

				<form style="margin:0;" action="<?PHP $PHP_SELF = $_SERVER['PHP_SELF']; echo $PHP_SELF; ?>" method="post" target="_parent" enctype="multipart/form-data">
				<input type="hidden" name="dpflege" value="1">
				<input type="hidden" name="d_upload" value="1" />
				<input type="hidden" name="menunr" value="<?php echo $menunr; ?>">
				<input type="hidden" name="idbaustein" value="<?php echo $daten[idbaustein]; ?>">
				<input type="hidden" name="idreihenfolge" value="<?php echo $daten[idreihenfolge]; ?>">
				<input type="hidden" name="idtbaustein" value="<?php echo $daten[idtbaustein]; ?>">
				<input type="hidden" name="idbbaustein" value="<?php echo $daten[idbbaustein]; ?>">
				<input type="hidden" name="idlink" value="<?php echo $daten[idlink]; ?>">
				<input type="hidden" name="idgbaustein" value="<?php echo $daten[idgbaustein]; ?>">
				<input type="hidden" name="idmenu" value="<?php echo $daten[idmenu]; ?>">
				<input type="hidden" name="aart" value="<?php echo $daten[aart]; ?>">
				<input type="hidden" name="name" value="<?php echo $daten[name]; ?>">
				<input type="hidden" name="zeile" value="<?php echo $daten[zeile]; ?>">
				<input type="hidden" name="spalte" value="<?php echo $daten[spalte]; ?>">
				<input type="hidden" name="zeilenumbruch" value="<?php echo $daten[zeilenumbruch]; ?>">
				<input type="hidden" name="zentriert" value="<?php echo $daten[zentriert]; ?>">
				<input type="hidden" name="lname" value="<?php echo $kdaten[name]; ?>">
				<input type="hidden" name="textgroesse" value="<?php echo $daten[textgroesse]; ?>">
				<input type="hidden" name="fett" value="<?php echo $daten[fett]; ?>">
				<input type="hidden" name="kursiv" value="<?php echo $daten[kursiv]; ?>">
<! hier wird der name des festgelegt, wie dieser bei der nachfolgenden bearbeitung benannt wird>
				<center>	
				<input type="hidden" name="file" value="dokument" />
				<input type="file" name="dokument" />
				<input type="submit" id="sbutton" value="Dokument hochladen" />
				</form>

			<?php
				};

			};

// Gaestebuch einfuehgen		
		if($daten[aart]=="3")
			{
			$kergebnis=mysql_query("SELECT * FROM gbaustein WHERE reihenfolge_idreihenfolge='$daten[idreihenfolge]'");
			$kzahl=mysql_num_rows($kergebnis);
			if($kzahl=="0")
				{?>
				Nummer eingeben:<input type="text" size="1" name="gbnr" >
				</td></tr></table>
				</td></tr><tr><td>			
				<?php
				}
				else
				{;
				$kergebnis=mysql_query("SELECT * FROM gbaustein WHERE reihenfolge_idreihenfolge='$daten[idreihenfolge]'");
				$kzahl=mysql_num_rows($kergebnis);
				$kdaten=mysql_fetch_assoc($kergebnis);
				?>
				<font size="3">
				<center><sbutton><?php echo "Nr: ",$kdaten[idgbaustein]," - IP: ",$kdaten[ip]," - Am: ",$kdaten[anlagedatum]; ?></sbutton></center>
				Name: <input type="text" size="27" name="gbname" value="<?php echo $kdaten[name]; ?>">
				<br>Textgr&ouml;sse: <input type="text" size="1" name="textgroesse" value="<?php echo $daten[textgroesse]; ?>">
				Fett:<input type="checkbox" size="1" value="1" name="fett" <?php if($daten[fett]=="1"){echo "checked";}; ?>>	
				Kursiv:<input type="checkbox" size="1" value="1" name="kursiv" <?php if($daten[kursiv]=="1"){echo "checked";}; ?>>
				Abuse:<input type="checkbox" size="1" value="1" name="abuse" <?php if($kdaten[abuse]=="1"){echo "checked";}; ?>>		
			</font>
			</td></tr></table>
			</td></tr><tr><td>
				<textarea name="text" rows="5" cols="111" ><?php echo $kdaten[text]; ?></textarea>
				<input type="hidden" name="gbnr" value="<?php echo $kdaten[idgbaustein]; ?>">
				<?php
				};
				?>
			
			<center><input type="submit" id="sbutton" class="sd" style=width:120px; name="senden" value="&Auml;ndern" />	
			</form>
			<?php
			if($_POST[gsuch]=="1")
				{$ggsuch1="%".$_POST[gbsuch1]."%";
				$ggsuch2="%".$_POST[gbsuch2]."%";
				$ggsuch3="%".$_POST[gbsuch3]."%";
				$gssuch=mysql_query("SELECT * FROM gbaustein WHERE text like '$ggsuch1' AND text like '$ggsuch2' AND text like '$ggsuch3'");
				$gssuchzahl=mysql_num_rows($gssuch);
				$z="0";
				WHILE($z<$gssuchzahl)
					{$gssuchdat=mysql_fetch_assoc($gssuch);
					 ?>
					
					<table border="1"><caption><p></p>Auswahlvorschlag <?php echo "Nr: ",$gssuchdat[idgbaustein]," - Name: ",$gssuchdat[name]," - Abuse: ",$gssuchdat[abuse]; ?>
					<hr style="color:red; background: red; height:5px;">
					</caption><tr><td width="90" valign="top">
					<textarea name="text" rows="3" cols="115" ><?php echo $gssuchdat[text]; ?></textarea> 
					</td></tr></table>
					<?php $z++;};
				}
			?>
			</td></tr><tr><td>
			<center>
			<form style="margin:0;" name="anzeige" action="<?PHP $PHP_SELF = $_SERVER['PHP_SELF']; echo $PHP_SELF; ?>" method="post" target="_parent">
			<input type="hidden" name="menunr" value="<?php echo $menunr; ?>">
			<input type="hidden" name="gsuch" value="1">
			<font size="2">
			Suchbegriff-1 :<input type="text" size="12" name="gbsuch1" >	
			Suchbegriff-2 :<input type="text" size="12" name="gbsuch2" >
			Suchbegriff-3 :<input type="text" size="12" name="gbsuch3" >		
			<center><input type="submit" id="sbutton" class="sd" style=width:120px; name="senden" value="Suchen" />
			<?php
			};		
		
// Terminbaustein
		if($daten[aart]=="4")
			{$kergebnis=mysql_query("SELECT * FROM bbaustein WHERE reihenfolge_idreihenfolge='$daten[idreihenfolge]'");
			$kzahl=mysql_num_rows($kergebnis);
			if($kzahl=="0")
				{mysql_query("INSERT INTO bbaustein (reihenfolge_idreihenfolge) VALUES ('$daten[idreihenfolge]'");};
			$kergebnis=mysql_query("SELECT * FROM bbaustein WHERE reihenfolge_idreihenfolge='$daten[idreihenfolge]'");
			$kzahl=mysql_num_rows($kergebnis);
			$kdaten=mysql_fetch_assoc($kergebnis);
			if($kdaten[bild]==""){$anzeig="Kein Plakat vorhanden<br>erst hochladen dann senden";}else{$anzeig="Plakat vorhanden, Updaten?";
										?>
										<input type="hidden" name="abild" value="<?php echo $kdaten[bild]; ?>">
										<?php										
										};
			if($kdaten[name]==NULL OR $kdaten[name]==""){$kdaten[name]=$daten[name];};	

			?>
			<input type="hidden" name="bbaustein" value="<?php echo $kdaten[idbbaustein]; ?>">
			Bildname: <input type="text" size="12" name="bdname" value="<?php echo $kdaten[name]; ?>">
			<br>
			Bild % -Darstellung: <input type="text" size="2" name="bprozent" value="<?php echo $daten[bprozent]; ?>">
	
			</td><td>

			<?php echo "<center><font color='red' size='2'>", $anzeig;

			if($daten[a_datum]!=NULL){$datumanz=substr($daten[a_datum],0,4);$tag=substr($daten[a_datum],8,2); $monat=substr($daten[a_datum],5,2);
												$bstunde=substr($daten[a_datum],11,2);}
											else{$datumanz=substr($datum,0,4);$tag=substr($datum,8,2);$monat=substr($datum,5,2);
												$bstunde=substr($datum,11,2);};
			if($daten[e_datum]!=NULL){$estunde=substr($daten[e_datum],11,2);}else{$estunde=substr($datum,11,2);};
			if($daten[l_datum]!=NULL){$zabfrage="SELECT unix_timestamp(a_datum) AS 'adatum',unix_timestamp(l_datum) AS 'ldatum'  FROM baustein WHERE idbaustein='$_SESSION[bnr]'";
											$zergebnis=mysql_query($zabfrage);
											$zdaten=mysql_fetch_assoc($zergebnis);
											$tage=round(($zdaten[ldatum]-$zdaten[adatum])/86400)+1;};
			$tergebnis=mysql_query("SELECT * FROM tbaustein WHERE reihenfolge_idreihenfolge='$daten[idreihenfolge]'");
			$tzahl=mysql_num_rows($tergebnis);
			if($tzahl=="0")
				{mysql_query("INSERT INTO tbaustein (reihenfolge_idreihenfolge, name) VALUES ('$daten[idreihenfolge]','$daten[name]')") or die ("dbweg");};
			$tergebnis=mysql_query("SELECT * FROM tbaustein WHERE reihenfolge_idreihenfolge='$daten[idreihenfolge]'");
			$tzahl=mysql_num_rows($tergebnis);
			$tdaten=mysql_fetch_assoc($tergebnis);
			if($tdaten[name]==NULL OR $tdaten[name]==""){$tdaten[name]=$daten[name];};		
			 ?>	
			<br></font><font size="2">T: <input type="text" name="tag" value="<?php echo $tag; ?>" size="1" maxlength="3">
			<input type="hidden" name="idtbaustein" value="<?php echo $tdaten[idtbaustein]; ?>">
			<input type="text" name="monat" value="<?php echo $monat; ?>" size="1" maxlength="3">
			<input type="radio" name="jahr" value="<?php echo $datumanz; ?>" checked><?php echo $datumanz; ?>
			<input type="radio" name="jahr" value="<?php echo $datumanz+1; ?>" ><?php echo $datumanz+1; ?>
			<br>von: <input type="text" name="bstunde" value="<?php echo $bstunde ; ?>" size="1" maxlength="3">
			bis: <input type="text" name="estunde" value="<?php echo $estunde ; ?>" size="1" maxlength="3">
			f&uuml;r: <input type="text" name="ldatum" value="<?php echo $tage ; ?>" size="1" maxlength="3">
			Tage n.V.		
			</td></tr></table>
			<textarea name="text" rows="5" cols="111" ><?php echo $tdaten[text]; ?></textarea>
			<center><input type="submit" id="sbutton" class="sd" style=width:120px; name="senden" value="&Auml;ndern" />	
			</form>
			<?php
// Bildauswahl erstellen
			if($kdaten[bild]=="")
				{$blist=scandir(image);
				?>
				<center>	Oder:			
				<form style="margin:0;" name="b_auswahl" action="<?PHP $PHP_SELF = $_SERVER['PHP_SELF']; echo $PHP_SELF; ?>" method="post" target="_parent">
				<input type="hidden" name="menunr" value="<?php echo $menunr; ?>">
				<input type="hidden" name="idbaustein" value="<?php echo $daten[idbaustein]; ?>">
				<input type="hidden" name="idreihenfolge" value="<?php echo $daten[idreihenfolge]; ?>">
				<input type="hidden" na1me="idtbaustein" value="<?php echo $daten[idtaustein]; ?>">
				<input type="hidden" name="idbbaustein" value="<?php echo $daten[idbbaustein]; ?>">
				<input type="hidden" name="idlink" value="<?php echo $daten[idlink]; ?>">
				<input type="hidden" name="idgbaustein" value="<?php echo $daten[idgbaustein]; ?>">
				<input type="hidden" name="idmenu" value="<?php echo $daten[idmenu]; ?>">
				<input type="hidden" name="aart" value="<?php echo $daten[aart]; ?>">
				<input type="hidden" name="bbaustein" value="<?php echo $kdaten[idbbaustein]; ?>">
			
				<select name="bildwahl" size="0" onChange="document.b_auswahl.submit()" >
				<?php
				foreach($blist as $bnr)
					{if($bnr!="." AND $bnr!="..")
						{echo "<option>",substr($bnr,0,-4),"</option>";
						};
					};
				?>				
				</select>
				<input type="submit" id="sbutton" class="sd" style=width:150px; name="senden" value="Dokument w&auml;hlen" />	
				</form>
				<center>Oder:
				<?php	
				};
				?>					
			</td></tr><tr><td>

				
<! uploadformular>

			<form style="margin:0;" action="<?PHP $PHP_SELF = $_SERVER['PHP_SELF']; echo $PHP_SELF; ?>" method="post" target="_parent" enctype="multipart/form-data">
			<input type="hidden" name="menunr" value="<?php echo $menunr; ?>">
			<input type="hidden" name="idbaustein" value="<?php echo $daten[idbaustein]; ?>">
			<input type="hidden" name="idreihenfolge" value="<?php echo $daten[idreihenfolge]; ?>">
			<input type="hidden" name="idtbaustein" value="<?php echo $daten[idtaustein]; ?>">
			<input type="hidden" name="menu" value="<?php echo $menudaten[menu]; ?>">
			<input type="hidden" name="hmstellung" value="<?php echo $menudaten[hmstellung]; ?>">
			<input type="hidden" name="idbbaustein" value="<?php echo $daten[idbbaustein]; ?>">
			<input type="hidden" name="idlink" value="<?php echo $daten[idlink]; ?>">
			<input type="hidden" name="idgbaustein" value="<?php echo $daten[idgbaustein]; ?>">
			<input type="hidden" name="idmenu" value="<?php echo $daten[idmenu]; ?>">
			<input type="hidden" name="aart" value="<?php echo $daten[aart]; ?>">
			<input type="hidden" name="bbaustein" value="<?php echo $kdaten[idbbaustein]; ?>">
			<center>
			<input type="hidden" name="upload" value="1" />
			<input type="hidden" name="idreihenfolge" value="<?php echo $daten[idreihenfolge]; ?>">
			<input type="hidden" name="bbaustein" value="<?php echo $kdaten[idbbaustein]; ?>">
<! hier wird der name des festgelegt, wie dieser bei der nachfolgenden bearbeitung benannt wird>	
			<input type="hidden" name="file" value="bild" />
			<input type="file" name="bild" />
			<input type="submit" id="sbutton" value="Bild hochladen" />
			</form>
			<?php
			if($kdaten[bild]=="")
				{ ?>
				</td></tr><tr><td>
				<table border="1" width="100%"><tr><td>
				<?php
				reset ($blist);
				$zl="1";
				foreach($blist as $bnr)
					{if($bnr!="." AND $bnr!="..")
						{if($zl/4==round($zl/4)){$ws="</td></tr><tr><td>";}else{$ws="</td><td>";};
						$banz="<a href='image/".$bnr."' target='_blank' ><img src='vorschau/".$bnr."' alt='Bild gestoert' /></a>";
						echo "<center>",$bnr,"<br>",$banz,$ws;
						$zl++;
						};
					};
				echo "</td></tr></table>";				
				};

			};
		
		
		};
		?>		
	</td></tr></table>

	
	<?php
	};
	
	
// Normaldarstellung

echo "<font size='6'>",$menudaten[ueberschrift], "</font><p>";

	unset($anzeige);
	$zeile="1";
	$spalte="1";
	$reihe="1";
	$anzeige="<center><table width='800'><tr><td valign='top'>";
	$abfrage="SELECT * FROM baustein JOIN reihenfolge ON  baustein.menu_idmenu='$menunr' AND reihenfolge.baustein_idbaustein=baustein.idbaustein 
		AND (l_datum IS NULL OR l_datum>'$datum')
		ORDER BY baustein.zeile, baustein.spalte, reihenfolge.reihenfolge";
	$ergebnis=mysql_query($abfrage);
	$anzahl=mysql_num_rows($ergebnis);
	$z="0";
	
	while($z<$anzahl)
		{$daten=mysql_fetch_assoc($ergebnis);
		if($daten[aart]=="3"){$eabfrage="SELECT * FROM gbaustein WHERE reihenfolge_idreihenfolge='$daten[idreihenfolge]' AND abuse='0'";$aart="Gaestebuch";};
		if($daten[aart]=="2"){$eabfrage="SELECT * FROM link WHERE reihenfolge_idreihenfolge='$daten[idreihenfolge]'";$aart="Link";};
		if($daten[aart]=="1" OR $daten[aart]=="4"){$eabfrage="SELECT * FROM bbaustein WHERE reihenfolge_idreihenfolge='$daten[idreihenfolge]'";$aart="Bild";};
		if($daten[aart]=="0"){$eabfrage="SELECT * FROM tbaustein WHERE reihenfolge_idreihenfolge='$daten[idreihenfolge]'";$aart="Text";};
		$eergebnis=mysql_query($eabfrage);
		$edaten=mysql_fetch_assoc($eergebnis);
		if($zeile<$daten[zeile]){$spalte="1";$zeile=$daten[zeile];$zvs="</td></tr></table><table width='800'><tr><td valign='top'>";}else{$zvs="";};
		if($spalte<$daten[spalte]){$spalte=$daten[spalte];$svs="</td><td valign='top'>";}else{$svs="";};
		if($daten[zeilenumbruch]=="1"){$lf="<br>";}else{$lf="";};
		if($daten[fett]=="1"){$fanz=$fanz."<strong>";$eanz=$eanz."</strong>";};
		if($daten[zentriert]=="1"){$zent="<center>";}else{$zent="";};
		if($daten[kursiv]=="1"){$fanz=$fanz."<em>";$eanz=$eanz."</em>";};
		$fanz=$fanz."<font size='".$daten[textgroesse]."'>";	
		$eanz=$eanz."</font>".$lf;	
		$text=nl2br($edaten[text]);	
		unset($banzg);	
		if($daten[aart]=="0"){$anzeige=$anzeige.$zvs.$svs.$fanz.$zent.$text.$eanz;};
		if($daten[aart]=="1"){$bdn=$edaten[bild].".png";
									if($daten[vorschau]=="1"){$banzg="<a href='image/".$bdn."' target='_blank' ><img src='vorschau/".$bdn."' alt='Bild gestoert' /></a>";}
									else{$banzg="<img src='image/".$bdn."' width='".$daten[bprozent]."' alt='nicht vorhanden'>";};		
									$anzeige=$anzeige.$zvs.$svs.$zent.$banzg.$lf ;};
		if($daten[aart]=="2"){
									$lanz="<a href='http://".$edaten[link]."' target='_blank'>".$edaten[name]."</a>"; 
									$anzeige=$anzeige.$zvs.$svs.$fanz.$zent.$lanz.$eanz;};
		if($daten[aart]=="3"){$anzeige=$anzeige.$zvs.$svs.$fanz.$zent.$edaten[text].$eanz;};
		if($daten[aart]=="4"){$bdn=$edaten[bild].".png";
									$banzg="<a href='plakat.php?bdnr=".$bdn."' target='_blank' ><img src='image/".$bdn."' width='100' alt='nicht vorhanden'></a>";		
									$ttergebnis=mysql_query("SELECT * FROM tbaustein WHERE reihenfolge_idreihenfolge='$daten[idreihenfolge]'");
									$ttdaten=mysql_fetch_assoc($ttergebnis);
									$text=nl2br($ttdaten[text]);
									$aart="Termin";
									$anzeige=$anzeige.$zvs."</td><td width='140' valign='top'><center>Am: ".substr($daten[a_datum],8,2)."-".substr($daten[a_datum],5,2)."-".substr($daten[a_datum],0,4)."<br>Von: ".substr($daten[a_datum],11,2)." Bis: ".substr($daten[e_datum],11,2)." Uhr </td><td>".$text."</td></td><td  width='100' valign='top'><center>".$banzg;};

		if($_SESSION[admin]=="1")
			{$anzeige=$anzeige."<font size='1' color='red'><br>".$aart."-Name: ".$daten[name]." Zeile: ".$daten[zeile]." Spalte: ".$daten[spalte]."<br></font>";};


		unset($fanz);
		unset($eanz);
		$z++;};
		echo $anzeige, "<br>";		
	

	?>
	</td></tr></table><p><center><font size=1>Die Seite wurde erstellt als "Open-Source" unter GNU-Lizenz.</font>
	</td></tr></table>


</div>


<?php

mysql_close();
?>
</body></html>
