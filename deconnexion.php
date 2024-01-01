<?php
session_start();
// Détruire toutes les données de la session
session_destroy();

// Rediriger vers la page principale (ou une autre page après la déconnexion)
header("Location:index.php?page=dashboard");  // Assurez-vous de remplacer "index.php" par le chemin de votre page principale
exit();
?>
