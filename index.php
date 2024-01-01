<?php
session_start();

// Inclusion des fichiers principaux
include_once 'config/config.php';
include_once 'classes/models/Connexion.php';

// DEFINITION DES VARIABLES DE SESSION

// Définition de la page courante
if (isset($_GET['page']) and !empty($_GET['page'])) {
    $page = trim(strtolower($_GET['page'])); // enlever les espaces et mettre en minuscule

} else {
    header('Location:index.php?page=dashboard');
}

// Array contenant toutes les pages
$allPages = scandir('controllers/');





//Connexion au logiciel
if (in_array($page . 'Controller.php', $allPages)) {   
    include_once 'controllers/' . $page . 'Controller.php';
    include_once 'views/' . $page . 'View.php';
} else {
   // header('Location: index.php?page=404');
   
   //header('Location: 404.php');
   //include_once '404.php';
}
