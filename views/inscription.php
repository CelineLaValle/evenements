<?php
// Connexion à la base de données 
$conn = new mysqli("localhost", "root", "", "evenements");

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de connexion : " . $conn->connect_error);
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données et sécuriser contre XSS
    $nom = htmlspecialchars(trim($_POST['nom']));
    $prenom = htmlspecialchars(trim($_POST['prenom']));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $mot_de_passe = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hachage du mot de passe
    $role = 'utilisateur';  // Attribuer le rôle par défaut

    // Vérifier si l'email est déjà utilisé
    $sql = "SELECT id FROM user WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>alert('Cet email est déjà utilisé.');</script>";
    } else {
        // Insérer l'utilisateur
        $sql = "INSERT INTO user (nom, prenom, email, mot_de_passe, role) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $nom, $prenom, $email, $mot_de_passe, $role);

        if ($stmt->execute()) {
            header("Location: index.php?message=inscription_reussie");
            exit();
        } else {
            header("Location: index.php?message=erreur_inscription");
            exit();
        }
    }

    // Fermer la connexion
    $stmt->close();
    $conn->close();
}
?>


<form class="h-screen flex items-center justify-center" method="POST">
  <div class="text-center">
    <h1 class="text-2xl mb-6 font-['Irish_Grover']">S'inscrire</h1>
    <input type="nom" name="nom" placeholder="Nom" class="block w-64 mx-auto mb-4 p-2 rounded-[20px] bg-[#82D9E9] text-center text-gray-500 font-['Irish_Grover']">
    <input type="prenom" name="prenom" placeholder="Prénom" class="block w-64 mx-auto mb-4 p-2 rounded-[20px] bg-[#82D9E9] text-center text-gray-500 font-['Irish_Grover']">
    <input type="email" name="email" placeholder="Email" class="block w-64 mx-auto mb-4 p-2 rounded-[20px] bg-[#82D9E9] text-center text-gray-500 font-['Irish_Grover']">
    <input type="password" name="password" placeholder="Mot De Passe" class="block w-64 mx-auto mb-4 p-2 rounded-[20px] bg-[#82D9E9] text-center text-gray-500 font-['Irish_Grover']">
    <button class="w-32 mx-auto p-2 rounded-[20px] bg-[#82D9E9] text-center text-black font-['Irish_Grover']">Inscription</button>
  </div>
</form>