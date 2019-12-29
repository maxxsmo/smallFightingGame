# smallFightingGame

Apprentissage de la POO en PHP avec un petit jeu de combat :

- une classe personnage.
  - chaque objet possède des attributs privés (nom, dégats)
  - un personnage peut en frapper un autre et lui infliger des degats. 
  - à partir de de 100 points de dégats le personnage frappé est K.O.
- un manager pour la connexion à la BDD.
- une interface graphique minimaliste.

## Ajout de features supplémentaires (Pseudo code) :

### Ajout du niveau et de l’expérience :

__EXPERIENCE ?__
* experience compris entre 0 et 10.
* a chaque fois qu’un perso frappe il gagne 2 points d’expérience. Une fois à 10 il monte d’un niveau. 

__NIVEAUX ?__
* Le premier perso qui arrive au niveau 3 gagne la partie. 

__REFLEXION :__

* class Personnage : 
    - [x]  2 nouveaux attributs privés: niveau , experience.
    - [x] nouvelles constantes : const PERSONNAGE_MONTE_NIVEAU = 4; const PERSONNAGE_GAGNE = 5;
    - [x] établir les functions getters pour les nouveaux attributs privés.
    - [x] établir les fonctions setters (penser au principe d’encapsulation).
    - [x] créer fonction addNiveau()
        - [x] incrémenter le niveau de 1. = $this->_niveau += 1;
        - [x] If ($this->_niveau >= 3) {return self::PERSONNAGE_GAGNE;}
    - [x] créer fonction addExperience()
        - [x] $this->_experience += 2;
        - [x] if (experience >= 10) => return self::PERSONNAGE_MONTE_NIVEAU;
	

* class PersonnagesManager : 
    - [x] update méthode add.
    - [x] mettre à jour la méthode update
        * ajouter dans requête après SET experience, niveau. + ajout des PDOStatement::bindValue
    - [x] nouvelle méthode resetExperience(Personnage $perso)
        * requête sql ‘UPDATE personnages SET  experience = 0  WHERE id = ‘ .$perso->id()

* dans index.php : 
    - [x] update la partie information du personnage.
    - [x] mettre à jour les infos de la liste de personnage avant le bouton frapper de chaque perso.
    - [x] mettre à jour le switch / case de la méthode frapper().
        - [x] case Personnage::PERSONNAGE_FRAPPE => appeler la fonction addExperience();
    - [x] new if statement : if Personnage::PERSONNAGE_MONTE_NIVEAU 
            - [x] appeler $perso->addNiveau()
            - [x] appeler $perso>setExperience(0);
            - [x] $manager->update($perso).
        - [x] if Personnage::PERSONNAGE_GAGNE => return $winnerMessage.
        - [x] end if.
    - [x] end if 
    - [x] ;if(isset($winnerMessage) => fin de script.