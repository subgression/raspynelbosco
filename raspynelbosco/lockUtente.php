<title>Riproduttore - Lock Utente</title>
<?php
  include("Riproduttore.php");

  $riproduttore = new Riproduttore();
  print_r($riproduttore->getLockUtente());
?>
