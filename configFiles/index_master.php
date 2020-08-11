<title>Riproduttore - Server</title>
<?php
  error_reporting(E_ALL);
  ini_set('display_errors', true);

  include("Riproduttore.php");
  $riproduttore = new Riproduttore();

  $slaves = array();
  array_push($slaves, "192.168.2.2");
  array_push($slaves, "192.168.2.3");
  array_push($slaves, "192.168.2.4");
  array_push($slaves, "192.168.2.5");

  if (isset($_GET['user'])) {
    $user = $_GET['user'];
    if ($riproduttore->checkTracciaInRiproduzione()) {
      $riproduttore->setTracceVLC($user, $slaves);
    }
    else {
      echo "Una traccia è già in riproduzione!!";
    }
    exit;
  }

?>

