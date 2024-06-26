<?php

// on va chercher le chemin de ExempleMapping
use model\Mapping\ExempleMapping;
use model\Mapping\PermissionMapping;
use model\Mapping\TagMapping;
// session
session_start();

// Appel de la config
require_once "../config.php";

// our autoload
spl_autoload_register(function ($class) {
    $class = str_replace('\\', '/', $class);
    require PROJECT_DIRECTORY. '/' .$class . '.php';
});

$exemple1 = new PermissionMapping([
    "permission_id" => 1,
    "permission_name" => "exemple1",
    "permission_description" => "description1"
  
]);

$exemple2 = new TagMapping([
    "tag_id" => 1,
    "tag_slug" => "exemple2",
    
  
]);

var_dump($exemple1,$exemple2);
