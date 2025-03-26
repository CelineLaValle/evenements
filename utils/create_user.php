<?php

require_once './config/database.php';

/**
 * Vérifie si un email existe déjà dans la base de données.
 *
 * @param PDO $pdo Instance de la connexion PDO
 * @param string $email Email à vérifier
 * @return bool True si l'email existe, False sinon
 */
function emailExiste($pdo, $email) {
    $sql = "SELECT id_user FROM user WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    return $stmt->fetch() ? true : false;
}

/**
 * Ajoute un nouvel utilisateur à la base de données.
 *
 * @param PDO $pdo Instance de la connexion PDO
 * @param string $nom Nom de l'utilisateur
 * @param string $prenom Prénom de l'utilisateur
 * @param string $email Email de l'utilisateur
 * @param string $mot_de_passe Mot de passe haché
 * @param string $role Rôle de l'utilisateur (par défaut : "utilisateur")
 * @return bool True si l'insertion a réussi, False sinon
 */
function ajouterUtilisateur($pdo, $nom, $prenom, $email, $mot_de_passe, $role = 'utilisateur') {
    if (!empty($nom) && !empty($prenom) && !empty($email) && !empty($mot_de_passe)) {
        $sql = "INSERT INTO user (nom, prenom, email, mot_de_passe, role) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$nom, $prenom, $email, $mot_de_passe, $role]);
    }
}

function modifierUtilisateur($pdo, $id, $nom, $prenom, $email, $mot_de_passe, $role = 'utilisateur') {
    if (!empty($nom) && !empty($prenom) && !empty($email) && !empty($mot_de_passe)) {
        $mot_de_passe = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hachage du mot de passe
        $sql = "UPDATE user SET nom = ?, prenom = ?, email = ?, mot_de_passe = ?, role = ? WHERE id_user = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$nom, $prenom, $email, $mot_de_passe, $role, $id]);
    }
}

?>