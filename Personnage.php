<?php 

class Personnage {

  private $_id;
  private $_nom;
  private $_degats = 0;

  const CEST_MOI = 1; // constante renvoyé par la méthode frapper() si on se frappe soi-même.
  const PERSONNAGE_TUE = 2; // CONST renvoyé par la méthode recevoirDegats() si on a tué le perso en frappant. 
  const PERSONNAGE_FRAPPE = 3; // CONST renvoyé par la méthode recevoirDegats si on a frappé le perso.

  public function __construct(array $data) {
    $this->hydrate($data);
  }

  public function hydrate(array $data) {
    // Boucle Foreach : $data as $key  => $value (pour chaque element de l'array faire :)
        // créer variable $method = "set". ucfirst($key); (accéder aux méthodes grace à la suntaxe 'set' + nom de la clé dans le tableau en mettant la première en maj. )
        //  if(method_exist($this, $method))
            //  retourner la méthode sous la forme : $this->$method($value);
        // end if 
    // end foreach
    
    foreach($data as $key => $value) 
    {
      $method = 'set' . ucfirst($key);
      if(method_exists($this, $method)) 
      {
        $this->$method($value);
      }
    }
  }

  public function nomValide() {
    return !empty($this->_nom);
  }
  

  public function frapper(Personnage  $perso) {
    if($this->_id == $perso->id())
    {
      return self::CEST_MOI;
    }
    return $perso->recevoirDegats();
  }



  public function recevoirDegats() {

    $this->_degats += 5;

    if($this->_degats >= 100) 
    {
      return self::PERSONNAGE_TUE;
    }

    
    return self::PERSONNAGE_FRAPPE;
  }

  // getters : 

  public function id() {return $this->_id;}
  public function nom() {return $this->_nom;}
  public function degats() {return $this->_degats;}


  //getters end.

  //setters

  public function setId($id) {

    $id = (int) $id;
    if($id > 0)
    {
      $this->_id = $id;
    }
  }

  public function setNom($nom) {

    if(is_string($nom))
    {
      $this->_nom = $nom;
    }
  }

  public function setDegats($degats) {

    $degats = (int) $degats;
    if($this->_degats >= 0 && $this->_degats < 100)
    {
      $this->_degats = $degats;
    }
  }
  // setters end.

}