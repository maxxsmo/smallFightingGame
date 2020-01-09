<?php

function autoLoad($cLasseName) {require $cLasseName . ".php";}
  spl_autoload_register('autoLoad');




$a = new Magicien(["nom" => "Mage"]);

echo (new Guerrier(["nom" => "warrior"]))->name();