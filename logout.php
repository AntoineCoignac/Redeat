<?php
session_start(); // Assurez-vous d'avoir appelé session_start() au début de votre script pour initialiser la session.

// Si vous voulez effacer complètement toutes les variables de session, vous pouvez réinitialiser la variable de session elle-même :
$_SESSION = array();

// Ensuite, vous pouvez également détruire la session pour garantir que toutes les données de session sont supprimées :
session_destroy();

header("Location: ./"); 
?>