<?php 

class PersonnagesManager {
 
  private $_db; // attribut qui représente l'objet d'accès à la BDD ( = PDO).

  public function __construct($db) {
    $this->setDb($db);
  }

  // setters : 
  public function setDb($db) {
    $this->_db = $db;
  }
  //end setters.


  // Queries 

  public function add(Personnage $perso) {

    $q = $this->_db->prepare("INSERT INTO personnages(nom) VALUES(:nom)");
    $q->bindValue(":nom", $perso->nom());
    $q->execute() or die(print_r($q->errorInfo()));
    
    $perso->hydrate(["id" => $this->_db->lastInsertId(), "degats" => 0]);

  }

  public function update(Personnage $perso) {

    $q = $this->_db->prepare("UPDATE personnages SET degats = :degats WHERE id = :id");
    $q->bindValue(":degats", $perso->degats(), PDO::PARAM_INT);
    $q->bindValue(":id", $perso->id(), PDO::PARAM_INT);
    $q->execute();
  }

  public function delete(Personnage $perso) {

    $this->_db->exec("DELETE FROM personnages WHERE id = ". $perso->id());

  }

  public function getPerso($var) {

    if(is_int($var))
    {
      $q = $this->_db->prepare("SELECT * FROM personnages WHERE id = :id");
      $q->bindValue(":id", $var, PDO::PARAM_INT);
      $q->execute();
      $data = $q->fetch(PDO::FETCH_ASSOC);
      return new Personnage($data);  
    }
   elseif(is_string($var))
   {
      $q = $this->_db->prepare("SELECT * FROM personnages WHERE nom = :nom ");
      $q->execute(array("nom" => $var));
      return new Personnage($q->fetch(PDO::FETCH_ASSOC));
   }
    

  }

  public function count() {

    return $this->_db->query("SELECT count(*) FROM personnages")->fetchColumn();

  }

  public function getList($var) {

    $persos = [];

    $q = $this->_db->prepare("SELECT * FROM personnages WHERE nom <> :nom ORDER BY nom");
    $q->execute(array("nom" => $var));

   while($data = $q->fetch(PDO::FETCH_ASSOC))
   {
     $persos[] = new Personnage($data);
   }

    return $persos;
  }


  public function exists($var) {
    if(is_int($var))
    {
      return (bool) $q = $this->_db->query("SELECT count(*) FROM personnages WHERE id = " . $var)->fetchcolumn();
    }
    elseif(is_string($var))
    {
      $q = $this->_db->prepare("SELECT count(*) FROM personnages WHERE nom = :nom");
      $q->execute(array("nom" => $var));
      return (bool) $q->fetchcolumn();
    }
    
  }

  // end Queries




}