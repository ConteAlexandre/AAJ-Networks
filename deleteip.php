<?php

include 'includes/pdo.php';

if (!empty($_GET['id'])&&is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    $sql = 'DELETE FROM server WHERE id = :id';
    $query = $pdo->prepare($sql);
    $query->bindValue(':id', $id, PDO::PARAM_STR);
    $query->execute();
    header('Location: addserver.php');
}

