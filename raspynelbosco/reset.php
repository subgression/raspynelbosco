<title>Riproduttore - Resetter </title>
<?php
  session_start();
  
  $_SESSION['indice'] = 0;

  include("Riproduttore.php");

  $riproduttore = new Riproduttore();
  $riproduttore->setLockUtente(new Utente('00-Mago'));

  echo "Sessione resettata correttamente";
?>
