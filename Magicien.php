<?php 

class Magicien extends Personnage {
  

  public function lancerSort($perso) {

    if($this->id != $perso->id() && $this->atout > 0)
    {
      // instructions
    }
  }
}