<?php
  mysqli_connect("localhost",  "root","7021983187","kkhw"); or die ("keine DB-Verbindung möglich");
  
Verbindung überprüfen
if (mysqli_connect_errno()) {
  printf("Verbindung fehlgeschlagen: %s\n", mysqli_connect_error());
  exit();
}
?>
  
