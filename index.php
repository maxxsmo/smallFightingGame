<?php
  ini_set('display_errors', 1);
  error_reporting(E_ALL); 

  function autoLoad($cLasseName) {require $cLasseName . ".php";}
  spl_autoload_register('autoLoad');

  session_start();

  if(isset($_GET["deconnexion"])) // si clique sur lien deconnexion, on détruit la session.
  {
    session_destroy();
    header("Location: .");
    exit();
  }

  if(isset($_SESSION["perso"])) // si la session existe on restaure l'objet 
  {
    $perso = $_SESSION["perso"];
  }
  

  $db = new PDO('mysql:host=localhost;dbname=miniJeuCombat', 'root', 'root');
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); // On émet une alerte à chaque fois qu'une requête a échoué.
  $manager = new PersonnagesManager($db);


 
// ********* FEED / DELETE DATABASE *******************
// *******************************************


  if(isset($_GET["feedDb"]))
  {
    $perso = new Personnage(["nom" => "geralt"]);

    if(!$manager->exists($perso->nom()))
    {
      $manager->add($perso); //création + ajout dans bdd
    }
    
  }

  if(isset($_GET["feedDb2"]))
  {
    $perso = new Personnage(["nom" => "xander"]);

    if(!$manager->exists($perso->nom()))
    {      
      $manager->add($perso); //création + ajout dans bdd
    }
  }

  if(isset($_GET["empty"])) // si utilisateur clique sur empty database.
  {
    $manager->empty();
  }

// ********* CREER UN PERSO *****************
// *******************************************


  if(isset($_POST["creer"]) && isset($_POST["nom"])) // si on veut créer un nouveau personnage
  {
    $perso = new Personnage(["nom" => $_POST["nom"]]); // création nouveau perso
    if(!$perso->nomValide())
    {
      $message = "ce nom est invalide";
      unset($perso);
    }
    elseif($manager->exists($perso->nom()))
    {
      $message = "le nom du perso est déjà pris";
      unset($perso);
    }
    else 
    {
      $manager->add($perso); //création + ajout dans bdd
    }
  }
  elseif(isset($_POST["utiliser"]) && isset($_POST["nom"])) // si on veut utiliser un personnage.
  {
    if($manager->exists($_POST["nom"]))
    {
      $perso = $manager->getPerso($_POST["nom"]); // récupération du personnage. 
    }
    else
    {
      $message = "le personnage n'existe pas !";
    }
  }

// ********* FRAPPER UN PERSO *****************
// *******************************************


  if(isset($_GET["frapper"])) // si l'utilisateur clique sur frapper.
  {
    if(!isset($perso))
    { 
      $message = "le personnage n'existe pas.";
    }
    else
    {
      if(!$manager->exists((int) $_GET["frapper"]))
      {
        // $perso->frapper($unperso[$i]);
        $message = "impossible de frapper car le personnage n'existe pas.";
      }
      else
      {
        $persoAFrapper = $manager->getPerso((int) $_GET["frapper"]);

       $retour = $perso->frapper($persoAFrapper);

        switch ($retour)
        {
          case Personnage::CEST_MOI : 
            $message = "vous vous frappez vous-même";

          break;

          case Personnage::PERSONNAGE_FRAPPE : 
            $message = "vous avez frappé un personnage";

            $date = new DateTime();
            $date->setTimezone(new DateTimeZone('Europe/Paris'));

            $perso->setDateDernierCoup($date);

            $perso->CountCoup();
            $perso->addExperience();
            $manager->update($persoAFrapper);
            $manager->update($perso);
            $manager->updateDate($perso);
            

          break;

          case Personnage::PERSONNAGE_TUE : 
            $message = "le personnage frappé a été tué";

            $manager->delete($persoAFrapper);

          break;

        }

        
        if(Personnage::PERSONNAGE_MONTE_NIVEAU)
        {
          if($perso->experience() == 10)
          {
            $message = " YOUHOU ce perso gagne un niveau.";
            $perso->addNiveau();
            $perso->addPuissance();
            $perso->setExperience(0); 
            $manager->update($perso);
          }
          elseif($perso->niveau() == 3)
          {
            $winnerMessage = "Vous avez gagné";
          }
        }
        
      }
    } 
 
  }
?>


<?php 



?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<body>
<?php
if(isset($winnerMessage))
{
  echo "<h1>$winnerMessage</h1>";
  session_destroy();
  echo "<a href=\"index.php\">jouer encore</a>";
  exit;
}
if(isset($message))
{
  echo "<p>$message</p>";
}
if(isset($perso) && isset($date))
{
  echo "<p>date du dernier coup ".$perso->dateDernierCoup()->format("Y-m-d H:i:s")."</p>";
  echo "\n";
}
if(isset($perso))
{
  echo "<p>nombre de coup : " . $perso->nbCoup()."</p>";
}



?>

<p><a href="?empty=aaa">empty database</a></p>

<p><a href="?feedDb=a">add perso 1</a> / <a href="?feedDb2=b"> add perso 2</a></p>

<p>Nombre de personnages : <?php echo $manager->count(); ?></p>
  <form action="" method="post">
    <label>Nom: </label> 
    <input type="text" name="nom" maxlength="50" />
    <input type="submit" value="créer le perso" name="creer" />
    <input type="submit" value="utiliser le perso" name="utiliser" />
  </form>
  
  <?php 
  if(isset($perso)):
  ?>
  
  <a href="?deconnexion=abc">deconnexion</a>

  <fieldset style="margin-top: 20px;">
    <legend>Mes informations :</legend>
    <p><strong>Nom : </strong><?= htmlspecialchars($perso->nom())?></p>
    <p><strong>Degats : </strong><?= $perso->degats()?></p>
    <p><strong>Expérience : </strong><?= $perso->experience()?></p>
    <p><strong>Niveau : </strong><?= $perso->niveau()?></p>  
  </fieldset>

  <fieldset style="margin-top: 20px;">
    <legend>Qui frapper ?</legend>
    <?php 
    $persos = $manager->getList($perso->nom());
    $unperso = $persos;

    for($i=0; $i<count($persos);$i++):
    ?>
    <p>
      <strong>nom: </strong> <?= $unperso[$i]->nom()?> 
      <strong>degats: </strong> <?= $unperso[$i]->degats()?> 
      <strong>experience: </strong> <?= $unperso[$i]->experience()?>
      <strong>niveau: </strong> <?= $unperso[$i]->niveau()?>
      <a href="?frapper=<?= $unperso[$i]->id()?>">frapper</a>
    </p>
    <?php endfor; ?>
  
  
  </fieldset>
  <?php endif; ?>

  
</body>
</html>
<?php 
if(isset($perso)) // si un perso est créee on l'assigne à la variable de session "perso", et economiser une requête SQL.
{
  $_SESSION["perso"] = $perso;
}

?>

