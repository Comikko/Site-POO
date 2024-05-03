<?php
include_once ('templates/parts/header.php');
include_once ('controller/controller.php');

// Créer une instance de la classe Controller
$controller = new Controller();

// Appeler la méthode handleRequest de l'instance de la classe Controller
$controller->handleRequest();
include_once ('templates/parts/footer.php');
?>
