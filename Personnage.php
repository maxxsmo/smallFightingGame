<?php 

class Personnage {

  protected $id;
  protected $nom;
  protected $degats;
  protected $experience;
  protected $niveau;
  protected $puissance = 0;
  protected $date_dernier_coup;
  protected $nb_coup = 0;
  protected $type;
  protected $atout;

  const CEST_MOI = 1; // constante renvoyé par la méthode frapper() si on se frappe soi-même.
  const PERSONNAGE_TUE = 2; // CONST renvoyé par la méthode recevoirDegats() si on a tué le perso en frappant. 
  const PERSONNAGE_FRAPPE = 3; // CONST renvoyé par la méthode recevoirDegats si on a frappé le perso.
  const PERSONNAGE_MONTE_NIVEAU = 4;  

  const PERSONNAGE_GAGNE = 5; // const renvoyé par la méthode addNiveau() si un personnage atteint le niveau 3.

  public function __construct(array $data) {
    $this->hydrate($data);
    $this->type = strtolower(static::class);
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
    return !empty($this->nom);
  }


  

  public function frapper(Personnage  $perso) {
    
    if($this->id == $perso->id())
    {
      return self::CEST_MOI;
    }
    // elseif($perso->_degats >= 200) 
    // {
    //   return self::PERSONNAGE_TUE;
    // }
    // else
    // {
    //   $perso->_degats += $this->_puissance;
    //   return self::PERSONNAGE_FRAPPE;
    // }
    
    return $perso->recevoirDegats($this->puissance);
      
  }

  public function recevoirDegats($var) {

    $this->degats += $var;

    if($this->degats >= 200) 
    {
      return self::PERSONNAGE_TUE;
    }

    return self::PERSONNAGE_FRAPPE;
  }

  public function addNiveau() {

      $this->niveau += 1;

       
  }

  public function addExperience() {

    $this->experience += 2;

    if($this->experience == 10)
    {
      return self::PERSONNAGE_MONTE_NIVEAU;
    }
  }

  public function addPuissance() {
    if($this->puissance <= 15)
    {
      $this->puissance += 5;
    }
    
  }

  public function CountCoup() {

      $this->nb_coup += 1;
    
  }

  public function adjustAtout() {
    if($this->degats < 25)
    {
      $this->atout = 4;
    }
    elseif($this->degats < 50)
    {
      $this->atout = 3;
    }
    elseif($this->degats < 75)
    {
      $this->atout = 2;
    }
    elseif ($this->degats < 90) 
    {
      $this->atout = 1;
    }
    else
    {
      $this->atout = 0;
    }
  }

  // getters : 

  public function id() {return $this->id;}
  public function nom() {return $this->nom;}
  public function degats() {return $this->degats;}
  public function experience() {return $this->experience;}
  public function niveau() {return $this->niveau;}
  public function puissance() {return $this->puissance;}
  public function dateDernierCoup() {return $this->date_dernier_coup;}
  public function nbCoup() {return $this->nb_coup;}
  public function type() {return $this->type;}
  public function atout() {return $this->atout;}


  //getters end.

  //setters

  public function setId($id) {

    $id = (int) $id;
    if($id > 0)
    {
      $this->id = $id;
    }
  }

  public function setNom($nom) {

    if(is_string($nom))
    {
      $this->nom = $nom;
    }
  }

  public function setDegats($degats) {

    $degats = (int) $degats;
    if($this->degats >= 0 && $this->degats < 100)
    {
      $this->degats = $degats;
    }
  }

  public function setExperience($exp) {

    $exp = (int) $exp;
    if($exp >= 0 && $exp <= 10)
    {
      $this->experience = $exp;
    }
    
    
  }

  public function setNiveau($niveau) {

    $niveau = (int) $niveau;
    if($niveau >= 0 && $niveau <= 3)
    {
      $this->niveau = $niveau;
    }

  }

  public function setPuissance($puissance) {

    $puissance = (int) $puissance;
    if($puissance >= 0 && $puissance <= 15)
    {
      $this->puissance = $puissance;
    }
  }

  public function setDateDernierCoup($date) {
    
    $this->date_dernier_coup = $date;
  }

  public function setNbCoup($nbCoup) {

    $this->nb_coup = $nbCoup;
  }

  public function setType() {
    $this->type = strtolower(static::class);
  }

  public function setAtout($var) {

    $var = (int) $var;
    $this->atout = $var;

    
  }


  // setters end.

}