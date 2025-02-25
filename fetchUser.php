<?php

require './config/database.php';

$nom = "Admin";
$prenom = "Admin";
$email = "test@test.com";
$mot_de_passe = "123456";
// $role = 'admin';

$stm = $pdo->prepare('INSERT INTO user (nom, prenom, email, mot_de_passe) VALUES (:nom, :prenom, :email, :mot_de_passe)');
$stm->bindParam(':nom', $nom);
$stm->bindParam(':prenom', $prenom);
$stm->bindParam(':email', $email);
$stm->bindParam(':mot_de_passe', $mot_de_passe);
// $stm->bindParam(':role', $role);
$stm->execute();

// $stm = $pdo->prepare('SELECT * FROM utilisateur');
// $stm->execute();
// $users = $stm->fetchAll();
// var_dump($users);

?>