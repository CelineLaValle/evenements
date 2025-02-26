<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  var_dump($_SESSION);
  var_dump($_POST['email']);
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Identifiants stockés
  $email_stocke = "azerty@azerty.fr";
  $password_stocke = "azerty";
  $role_stock = "admin";

  // Vérifier les identifiants
  if ($email === $email_stocke && $password === $password_stocke) {
    $_SESSION['email'] = $email;
    $_SESSION['role'] = $role_stock;
  }


  // Redirection basée sur le rôle

  if (!isset($_SESSION['role']) || $_SESSION['role'] === 'utilisateur') {
    // Rediriger l'utilisateur vers la page d'accueil si ce n'est pas un admin
    header("Location: index.php");
}

  if (isset($_SESSION['role'])) {
    if ($_SESSION["role"] === "admin") {
      header('Location: ?page=page_admin');
    }
  }
}

?>

<form class="h-screen flex items-center justify-center" method="POST">
  <div class="text-center">
    <h1 class="text-2xl mb-6 font-['Irish_Grover']">Se Connecter</h1>
    <input type="email" name="email" placeholder="Identifiant" class="block w-64 mx-auto mb-4 p-2 rounded-[20px] bg-[#82D9E9] text-center text-gray-500 font-['Irish_Grover']">
    <input type="password" name="password" placeholder="Mot De Passe" class="block w-64 mx-auto mb-4 p-2 rounded-[20px] bg-[#82D9E9] text-center text-gray-500 font-['Irish_Grover']">
    <a href="?page=inscription" class="block mb-6 ml-[170px] text-gray-500 font-['Irish_Grover']">S'inscrire</a>
    <button class="w-32 mx-auto p-2 rounded-[20px] bg-[#82D9E9] text-center text-black font-['Irish_Grover']">Valider</button>
  </div>
</form>