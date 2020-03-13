<?php
  /**
   * Questa classe definisce un utente, che avrá la sua lista di tracce
   */
  class Utente
  {
    /**
     * Il nome completo della cartella
     */
    private $nomeCartella;
    /**
     * L'id dell'utente
     */
    private $id;
    /**
     * Il nome completo dell'utente
     */
    private $nome;
    /**
     * La lista delle tracce
    */
    private $listaTracce;

    function __construct($nomeCartella)
    {
      $split = explode("-", $nomeCartella);
      $this->nomeCartella = $nomeCartella;
      $this->id = (int)$split[0];
      $this->nome = $split[1];
      $this->ListaTracce = array();
      $this->generaListaTracce($nomeCartella);
    }

    /**
     * Genera una lista di tracce partendo dalla cartella dell'utente
     */
    function generaListaTracce($nomeCartella) {
      $lista = scandir("Tracce/" . $nomeCartella . "/");
      //Questo contatore mi serve per eliminare le prime due occorrenze nel foreach
      //che sarebbero . e ..
      foreach ($lista as $key => $nomeFile) {
        $primoCarattere = substr($nomeFile, 0, 1);
        if ($primoCarattere != ".") {
          $this->listaTracce[] = $nomeFile;
        }
      }
    }

    /**
     * Restituisce il numero di tracce presenti nella lista
     */
    function getNumeroTracce() {
      return count($this->listaTracce);
    }

    /**
     * Restituisce il numero completo della cartella
     */
    function getNomeCartella() {
      return (string)$this->nomeCartella;
    }

    /**
     * Restituisce l'id dell'utente
     */
    function getID() {
      return $this->id;
    }

    /**
     * Restituisce il path completo della traccia dandogli un indice
     */
    function getTraccaByIndice($indice) {
      return (string)$this->listaTracce[$indice];
    }

    /**
     * Restituisce il numero di tracce presenti all'interno della cartella dell'utente
     */
    function getConteggioTracce () {
      return count($this->listaTracce);
    }
  }
?>
