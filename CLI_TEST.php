<?php

function autoLoad($cLasseName) {require $cLasseName . ".php";}
  spl_autoload_register('autoLoad');



