<?php

require './config/database.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // var_dump($_SESSION);
  // var_dump($_POST['email']);
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Connexion à la BDD
  $stmt = $pdo->prepare("SELECT * FROM user WHERE email = :email");
  $stmt->execute(['email' => $email]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  // // Identifiants stockés
  // $email_stocke = "azerty@azerty.fr";
  // $password_stocke = "azerty";
  // $role_stock = "admin";

  // Si un utilisateur est trouvé
  if ($user) {
    // Vérification du mot de passe (assure-toi que les mots de passe sont hashés avec password_hash en BDD)
    if (password_verify($password, $user['mot_de_passe'])) {
      // Vérification si l'utilisateur est suspendu
      if ($user['suspendu'] == 1) {
        $error = "Votre compte est suspendu. Contactez un administrateur.";
      } else {
        // Si l'utilisateur n'est pas suspendu, on crée la session
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role']; // Assurez-vous que la colonne `role` existe en BDD

        // Redirection basée sur le rôle
        if ($_SESSION['role'] === 'admin') {
          header('Location: ?page=page_admin');
          exit();
        } else {
          header("Location: index.php");
          exit();
        }
      }
    } else {
      $error = "Mot de passe incorrect";
    }
  } else {
    $error = "Utilisateur non trouvé";
  }
}
?>

<form class="h-screen flex items-center justify-center" method="POST">
  <div class="text-center">
    <h1 class="text-2xl mb-6 font-['Irish_Grover']">Se Connecter</h1>
    <?php if (!empty($error)): ?>
      <p class="text-red-500 font-bold mb-4"><?php echo $error; ?></p>
    <?php endif; ?>
    <input type="email" name="email" placeholder="Identifiant" class="block w-64 mx-auto mb-4 p-2 rounded-[20px] bg-[#82D9E9] text-center text-gray-500 font-['Irish_Grover']">
    <input type="password" name="password" placeholder="Mot De Passe" class="block w-64 mx-auto mb-4 p-2 rounded-[20px] bg-[#82D9E9] text-center text-gray-500 font-['Irish_Grover']">
    <a href="?page=inscription" class="block mb-6 ml-[170px] text-gray-500 font-['Irish_Grover']">S'inscrire</a>
    <button class="w-32 mx-auto p-2 rounded-[20px] bg-[#82D9E9] text-center text-black font-['Irish_Grover']">Valider</button>
  </div>
</form>