<title>Riproduttore - Server</title>
<?php
  error_reporting(E_ALL);
  ini_set('display_errors', true);

  include("Riproduttore.php");
  $riproduttore = new Riproduttore();

  if (isset($_GET['user'])) {
    $user = $_GET['user'];
    if ($riproduttore->checkTracciaInRiproduzione()) {
      $riproduttore->setTracciaVLC($user);
    }
    else {
      echo "Una traccia è già in riproduzione!!";
    }
    exit;
  }

?>
