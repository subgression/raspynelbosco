<?php
  include("Utente.php");

  class Riproduttore
  {
    /**
     *  La lista degli utenti, definita dalle cartelle presenti all'interno della cartella
     *  tracce
     */
    private $listaUtenti;

    function __construct()
    {
      session_start();
      //echo "Riproduttore costruito correttamente! <br>";
      //Costruisco la lista degli utenti
      $this->listaUtenti = array();
      $this->generaListaUtenti();
    }

    /**
     *  Genera una lista di utenti partendo dalla cartella tracce
     *  andrá a generare automaticamente una coppia ID - Nome per ciascun
     *  cartella presente nella cartella Tracce, se formattata correttamente
     *  la formattazione corretta é xx-nome
     *  @param: user, l'utente che ha generato la richiesta
     */
    function generaListaUtenti() {
      $lista = scandir("Tracce/");
      foreach ($lista as $nomeFile) {
        //Elimino i primi due elementi (sono sicuramente . e ..)
        if (strpos($nomeFile, '.') === false) {
          $utente = new Utente($nomeFile);
          if (isset($utente)) $this->listaUtenti[] = $utente;
        }
      }
    }

    /**
     *  Genera l'array di tracce corrispondente all'utente
     *  @param: user, l'utente che ha generato la richiesta
     */
    function setTracce($user) {
      //Se l'utente é lo stesso incremento l'indice
      if ($user == $this->getLockUtente()->getID()) {
        echo "Stesso utente <br>";
        $utenteAttuale = $this->getLockUtente();
        $this->updateIndice();
        echo "Valore dell'indice: " .$this->getIndice(). "<br>";
        //echo "<strong>Utente: " .$utenteAttuale->getID(). " riproduce file " .$utenteAttuale->getTraccaByIndice($this->getIndice()) ."<strong><br>";
        $this->salvaSuLog("Incremento indice");
        $this->riproduciTraccia($this->getLockUtente());
        return "<strong> Stesso utente <strong> <br>";
      }
      //Altrimenti salvo il nuovo lock e passo alla riproduzione utente successiva
      else {
        echo "Cambio utente <br>";
        //Ottengo chi é l'utente che ha effettuato la richiesta
        foreach ($this->listaUtenti as $utente) {
          if ($utente->getID() == $user) {
            //Resetto l'indice
            $this->setLockUtente($utente);
            //echo "<strong>Utente: " .$utente->getID(). " riproduce file " .$utente->getTraccaByIndice($this->getIndice()) ."<strong><br>";
            $this->salvaSuLog("Cambio utente");
            $this->setIndice(0);
            $this->riproduciTraccia($this->getLockUtente());
            return "<strong>Cambio utente </strong><br>";
          }
        }
      }
      return "Errore di riproduzione 01: Arrivo a EOF";
    }

    /**
     *  Genera l'array di tracce corrispondente all'utente e lo invia anche ad uno slave
     *  @param: user, l'utente che ha generato la richiesta
     *  @param: slave, l'indirizzo IP dello slave
     */
    function setTracceSlave($user, $slave) {
      //Se l'utente é lo stesso incremento l'indice
      if ($user == $this->getLockUtente()->getID()) {
        echo "Stesso utente <br>";
        $utenteAttuale = $this->getLockUtente();
        $this->updateIndice();
        echo "Valore dell'indice: " .$this->getIndice(). "<br>";
        //echo "<strong>Utente: " .$utenteAttuale->getID(). " riproduce file " .$utenteAttuale->getTraccaByIndice($this->getIndice()) ."<strong><br>";
        $this->salvaSuLog("Incremento indice");
        foreach ($slave as $s) {
          $this->riproduciTracciaSlave($this->getLockUtente(), $s);
        }
        return "<strong> Stesso utente <strong> <br>";
      }
      //Altrimenti salvo il nuovo lock e passo alla riproduzione utente successiva
      else {
        echo "Cambio utente <br>";
        //Ottengo chi é l'utente che ha effettuato la richiesta
        foreach ($this->listaUtenti as $utente) {
          if ($utente->getID() == $user) {
            //Resetto l'indice
            $this->setLockUtente($utente);
            //echo "<strong>Utente: " .$utente->getID(). " riproduce file " .$utente->getTraccaByIndice($this->getIndice()) ."<strong><br>";
            $this->salvaSuLog("Cambio utente");
            $this->setIndice(0);
            foreach ($slave as $s) {
              $this->riproduciTracciaSlave($this->getLockUtente(), $s);
            }
            return "<strong>Cambio utente </strong><br>";
          }
        }
      }
      return "Errore di riproduzione 01: Arrivo a EOF";
    }

    /**
     *  Genera l'array di tracce corrispondente all'utente, senza slave, e la riproduce
     *  @param: user, l'utente che ha generato la richiesta
     */
    function setTracciaVLC($user) {
      //Se l'utente é lo stesso incremento l'indice
      if ($user == $this->getLockUtente()->getID()) {
        echo "Stesso utente <br>";
        $utenteAttuale = $this->getLockUtente();
        $this->updateIndice();
        echo "Valore dell'indice: " .$this->getIndice(). "<br>";
        //echo "<strong>Utente: " .$utenteAttuale->getID(). " riproduce file " .$utenteAttuale->getTraccaByIndice($this->getIndice()) ."<strong><br>";
        $this->salvaSuLog("Incremento indice");
        $this->precaricaTracciaVLC($this->getLockUtente(), 'localhost');
        shell_exec('sleep 1');
        $this->riproduciTracciaVLC($this->getLockUtente(), 'localhost');
        return "<strong> Stesso utente <strong> <br>";
      }
      //Altrimenti salvo il nuovo lock e passo alla riproduzione utente successiva
      else {
        echo "Cambio utente <br>";
        //Ottengo chi é l'utente che ha effettuato la richiesta
        foreach ($this->listaUtenti as $utente) {
          if ($utente->getID() == $user) {
            //Resetto l'indice
            $this->setLockUtente($utente);
            //echo "<strong>Utente: " .$utente->getID(). " riproduce file " .$utente->getTraccaByIndice($this->getIndice()) ."<strong><br>";
            $this->salvaSuLog("Cambio utente");
            $this->setIndice(0);
            $this->precaricaTracciaVLC($this->getLockUtente(), 'localhost');
            shell_exec('sleep 1');
            $this->riproduciTracciaVLC($this->getLockUtente(), 'localhost');
            return "<strong>Cambio utente </strong><br>";
          }
        }
      }
      return "Errore di riproduzione 01: Arrivo a EOF";
    }

    /**
     *  Genera l'array di tracce corrispondente all'utente e lo invia al web server VLC
     *  @param: user, l'utente che ha generato la richiesta
     *  @param: slave, l'indirizzo IP dello slave
     */
    function setTracceVLC($user, $slave) {
      //Se l'utente é lo stesso incremento l'indice
      if ($user == $this->getLockUtente()->getID()) {
        echo "Stesso utente <br>";
        $utenteAttuale = $this->getLockUtente();
        $this->updateIndice();
        echo "Valore dell'indice: " .$this->getIndice(). "<br>";
        //echo "<strong>Utente: " .$utenteAttuale->getID(). " riproduce file " .$utenteAttuale->getTraccaByIndice($this->getIndice()) ."<strong><br>";
        $this->salvaSuLog("Incremento indice");
        foreach ($slave as $s) {
          $this->precaricaTracciaVLC($this->getLockUtente(), $s);
        }
        foreach ($slave as $s) {
          $this->riproduciTracciaVLC($this->getLockUtente(), $s);
        }
        return "<strong> Stesso utente <strong> <br>";
      }
      //Altrimenti salvo il nuovo lock e passo alla riproduzione utente successiva
      else {
        echo "Cambio utente <br>";
        //Ottengo chi é l'utente che ha effettuato la richiesta
        foreach ($this->listaUtenti as $utente) {
          if ($utente->getID() == $user) {
            //Resetto l'indice
            $this->setLockUtente($utente);
            //echo "<strong>Utente: " .$utente->getID(). " riproduce file " .$utente->getTraccaByIndice($this->getIndice()) ."<strong><br>";
            $this->salvaSuLog("Cambio utente");
            $this->setIndice(0);
            foreach ($slave as $s) {
              $this->precaricaTracciaVLC($this->getLockUtente(), $s);
            }
            foreach ($slave as $s) {
              $this->riproduciTracciaVLC($this->getLockUtente(), $s);
            }
            return "<strong>Cambio utente </strong><br>";
          }
        }
      }
      return "Errore di riproduzione 01: Arrivo a EOF";
    }

    /**
     *  Controlla la presenza di una traccia in riproduzione tramite lo script getOMXPLayerStatus.sh
     *  restituisce 1 se non è presente alcuna traccia (l'unico processo con nome OMXplayer è grep omxplayer)
     *  restituendo un valore superiore ad 1 si può capire che esiste almeno un processo attivo di tipo OMXPlayer
     *  @return: true se non é presente una traccia, false se lo é
     */
    function checkTracciaInRiproduzione() {
      $res = shell_exec("./get_vlc_stats.sh");
      echo $res. "<br>";
      if ($res == 0) return true;
      return false;
    }

    /**
    * Riproduce una specifica traccia
    * la riproduzione è asincrona per evitare la formazione di code (| at now & disown)
    * @param: Utente: l'utente che vuole riprodurre una traccia
    */
    function riproduciTraccia($utente) {
      $nc = $utente->getNomeCartella();
      $t = $utente->getTraccaByIndice($this->getIndice());
      shell_exec('curl -u :raspy "localhost:9999/requests/status.xml?command=pl_empty"');
      $cmd_pi = 'curl -u :raspy "localhost:9999/requests/status.xml?command=in_play&input=file%3A%2F%2F%2Fvar%2Fwww%2Fhtml%2Fraspynelbosco%2FTracce%2F'.$nc.'%2F'.$t.'"';
      echo $cmd_pi . "<br>";
      $this->salvaSuLog($cmd_pi);
      shell_exec($cmd_pi);
      return $cmd_pi;
    }

    /**
    * Riproduce una specifica traccia e avvia la riproduzione anche allo slave
    * la riproduzione è asincrona per evitare la formazione di code (| at now & disown)
    * @param: Utente: l'utente che vuole riprodurre una traccia
    * @param: slave: l'indirizzo IP dello slave
    */
    function riproduciTracciaSlave($utente, $slave) {
      $nc = $utente->getNomeCartella();
      $t = $utente->getTraccaByIndice($this->getIndice());
      $cmd_slave = "curl http://" .$slave. "/raspynelbosco/index.php?user=" .$utente->getID(). " | at now & disown";
      echo "Eseguo comando sullo slave: " .$cmd_slave. "<br>";
      shell_exec($cmd_slave);
      return $cmd_slave;
    }

    /**
     * Precarica una specifica traccia sul webserver VLC
     * @param: Utente: l'utente che vuole riprodurre una traccia
     * @param: slave: L'indirizzo IP dello slave
     */
    function precaricaTracciaVLC($utente, $slave) {
      $nc = $utente->getNomeCartella();
      $t = $utente->getTraccaByIndice($this->getIndice());
      $c1 = 'curl -u :raspy --silent --output /dev/null "'.$slave.':9999/requests/status.xml?command=pl_empty"';
      $c2 = 'curl -u :raspy --silent --output /dev/null "'.$slave.':9999/requests/status.xml?command=in_enqueue&input=file%3A%2F%2F%2Fvar%2Fwww%2Fhtml%2Fraspynelbosco%2FTracce%2F'.$nc.'%2F'.$t.'"';
      echo $c1 . "<br>";
      echo $c2 . "<br>";
      shell_exec($c1);
      shell_exec($c2);
    }

    /**
    * Riproduce una specifica traccia tramite il web server VLC
    * la riproduzione è asincrona per evitare la formazione di code (| at now & disown)
    * @param: Utente: l'utente che vuole riprodurre una traccia
    * @param: slave: l'indirizzo IP dello slave
    */
    function riproduciTracciaVLC($utente, $slave) {
      $c3 = 'curl -u :raspy --silent --output /dev/null "'.$slave.':9999/requests/status.xml?command=pl_play&id=0" | at now & disown';
      echo $c3 . "<br>";
      shell_exec($c3);
    }

    /**
     *  Setta il lock di in riproduzione salvandolo su un file chiamato user.lock
     *  @param: Utente utente: l'utente che richiede il lock
     */
    function setLockUtente($utente) {
      $lock = fopen("./user.lock", "w+");
      echo "Setto il lock <br>";
      fwrite($lock, serialize($utente));
      fclose($lock);
    }

    /**
     *  Ritorna chi ha il lock della riproduzione
     *  @return: L'utente che detiene il lock in riproduzione
     */
    function getLockUtente() {
      $lock = fopen("./user.lock", "r") or die("Impossibile aprire il file");
      $file = fread($lock, filesize("./user.lock"));
      fclose($lock);
      return unserialize($file);
    }

    /**
     *  Aggiorna automaticamente l'indice
     */
    function updateIndice() {
      $utenteAttuale = $this->getLockUtente();
      $this->setIndice($this->getIndice() + 1);
      //Controllo se ha superato il limite
      if ($this->getIndice() >= $utenteAttuale->getNumeroTracce()) {
        $this->setIndice(0);
      }
    }

    /**
     *  Salva il risultato dell'operazione in un file riproduttore.log
     */
    function salvaSuLog($val) {
      $log = fopen("./riproduttore.log", "a") or die("Impossibile creare il log!");
      $data = date('m/d/Y h:i:s a', time());
      $entry = "[" .$data. "]" .$val. "\n";
      fwrite($log, $entry);
      fclose($log);
    }

    /**
     *  Setta il valore dell'indice
     */
    function setIndice($val) {
      $lock = fopen("./index.lock", "w");
      fwrite($lock, serialize($val));
      fclose($lock);
    }

    /**
     *  Restituisce il valore dell indice
     *  @return: Il valore dell'indice
     */
    function getIndice() {
      $lock = fopen("./index.lock", "r") or die("Impossibile aprire il file");
      $file = fread($lock, filesize("./index.lock"));
      fclose($lock);
      return unserialize($file);
    }
  }
?>
