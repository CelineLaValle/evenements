<?php
require_once './config/database.php';
require_once "./utils/create_user.php";

// Ajouter un utilisateur
if (isset($_POST['ajouter'])) {
     // Récupérer les données et sécuriser contre XSS
     $nom = htmlspecialchars(trim($_POST['nom']));
     $prenom = htmlspecialchars(trim($_POST['prenom']));
     $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
     $mot_de_passe = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hachage du mot de passe
 
     // Vérifier si l'email est déjà utilisé
     if (emailExiste($pdo, $email)) {
         header("Location: index.php?page=utilisateurs&message=erreur_inscription_email_utilise");
             exit();
     } else {
         // Ajouter l'utilisateur
         if (ajouterUtilisateur($pdo, $nom, $prenom, $email, $mot_de_passe)) {
             header("Location: index.php?page=utilisateurs&message=inscription_reussie");
             exit();
         } else {
             header("Location: index.php?page=utilisateurs&message=erreur_inscription");
             exit();
         }
     }
}

// Supprimer un utilisateur
if (isset($_GET['supprimer'])) {
    $stmt = $pdo->prepare("DELETE FROM user WHERE id_user = ?");
    $stmt->execute([$_GET['supprimer']]);
    header("Location: ?page=utilisateurs");
    exit();
}

// Suspendre ou réactiver un utilisateur
if (isset($_GET['suspendre'])) {
    $id = (int)$_GET['suspendre'];

    // Récupérer l'état actuel
    $stmt = $pdo->prepare("SELECT suspendu FROM user WHERE id_user = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Inverser l'état de suspension
        $nouvelEtat = $user['suspendu'] ? 0 : 1;

        // Mettre à jour la base de données
        $stmt = $pdo->prepare("UPDATE user SET suspendu = ? WHERE id_user = ?");
        $stmt->execute([$nouvelEtat, $id]);

        header("Location: ?page=utilisateurs");
        exit();
    }
}

// Modifier un utilisateur (récupération des données)
$utilisateur = null;
if (isset($_GET['modifier'])) {
    $stmt = $pdo->prepare("SELECT * FROM user WHERE id_user = ?");
    $stmt->execute([$_GET['modifier']]);
    $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Enregistrer les modifications
if (isset($_POST['modifier'])) {
    modifierUtilisateur($pdo,  $_POST['id'], $_POST['nom'], $_POST['prenom'], $_POST['mail'], $_POST['password']);
    header("Location: ?page=utilisateurs");
    exit();
}

// Récupérer tous les utilisateurs
$stmt = $pdo->query("SELECT * FROM user");
$utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<?php include './views/bouton.php'; ?>

<h2>Gestion des Utilisateurs</h2>

<!-- Formulaire d'ajout -->
<h3>Ajouter un Utilisateur</h3>
<form method="POST">
  <div>
   <input type="text" name="nom" placeholder="Nom" required>
    <input type="text" name="prenom" placeholder="Prénom" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Mot De Passe" required>
    <button type="submit" name="ajouter">Ajouter</button>
  </div>
</form>

<!-- Formulaire de modification -->
<?php if ($utilisateur): ?>
    <h3>Modifier un Utilisateur</h3>
    <form method="post">
        <input type="hidden" name="id" value="<?= $utilisateur['id'] ?>">
        <input type="text" name="nom" value="<?= htmlspecialchars($utilisateur['nom']) ?>" required>
        <input type="text" name="prenom" value="<?= htmlspecialchars($utilisateur['prenom']) ?>" required>
        <input type="email" name="email" value="<?= htmlspecialchars($utilisateur['email']) ?>" required>
        <input type="password" name="password" required>
        <button type="submit" name="modifier">Modifier</button>
    </form>
<?php endif; ?>

<!-- Liste des utilisateurs -->
<h3>Liste des Utilisateurs</h3>
<table>
    <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Email</th>
        <th>Action</th>
    </tr>
    <?php foreach ($utilisateurs as $org): ?>
        <tr>
            <td><?= $org['id_user'] ?></td>
            <td><?= htmlspecialchars($org['nom']) ?></td>
            <td><?= htmlspecialchars($org['prenom']) ?></td>
            <td><?= htmlspecialchars($org['email']) ?></td>
            <td>
                <a href="?page=utilisateurs&modifier=<?= $org['id_user'] ?>">✏️ Modifier</a> |
                <a href="?page=utilisateurs&supprimer=<?= $org['id_user'] ?>">🗑️ Supprimer</a>

                <?php if ($org['suspendu']) : ?>
        <a href="?page=utilisateurs&suspendre=<?= $org['id_user'] ?>" class="text-green-500">✅ Réactiver</a>
    <?php else : ?>
        <a href="?page=utilisateurs&suspendre=<?= $org['id_user'] ?>" class="text-red-500">🚫 Suspendre</a>
    <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
