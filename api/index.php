<?php
require_once 'BookController.php';

$controller = new BookController($pdo);
$controller->handleRequest();
?>
