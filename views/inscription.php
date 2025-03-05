<?php
// Connexion à la base de données 
require_once "./config/database.php";
require_once "./utils/create_user.php";

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données et sécuriser contre XSS
    $nom = htmlspecialchars(trim($_POST['nom']));
    $prenom = htmlspecialchars(trim($_POST['prenom']));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $mot_de_passe = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hachage du mot de passe

    // Vérifier si l'email est déjà utilisé
    if (emailExiste($pdo, $email)) {
        header("Location: index.php?page=inscription&message=erreur_inscription_email_utilise");
            exit();
    } else {
        // Ajouter l'utilisateur
        if (ajouterUtilisateur($pdo, $nom, $prenom, $email, $mot_de_passe)) {
            header("Location: index.php?message=inscription_reussie");
            exit();
        } else {
            header("Location: index.php?page=inscription&message=erreur_inscription");
            exit();
        }
    }
}
?>

<!-- Formulaire d'inscription -->
<form class="h-screen flex items-center justify-center" method="POST">
  <div class="text-center">
    <h1 class="text-2xl mb-6 font-['Irish_Grover']">S'inscrire</h1>
    <input type="text" name="nom" placeholder="Nom" class="block w-64 mx-auto mb-4 p-2 rounded-[20px] bg-[#82D9E9] text-center text-gray-500 font-['Irish_Grover']" required>
    <input type="text" name="prenom" placeholder="Prénom" class="block w-64 mx-auto mb-4 p-2 rounded-[20px] bg-[#82D9E9] text-center text-gray-500 font-['Irish_Grover']" required>
    <input type="email" name="email" placeholder="Email" class="block w-64 mx-auto mb-4 p-2 rounded-[20px] bg-[#82D9E9] text-center text-gray-500 font-['Irish_Grover']" required>
    <input type="password" name="password" placeholder="Mot De Passe" class="block w-64 mx-auto mb-4 p-2 rounded-[20px] bg-[#82D9E9] text-center text-gray-500 font-['Irish_Grover']" required>
    <button type="submit" class="w-32 mx-auto p-2 rounded-[20px] bg-[#82D9E9] text-center text-black font-['Irish_Grover']">Inscription</button>
  </div>
</form>
